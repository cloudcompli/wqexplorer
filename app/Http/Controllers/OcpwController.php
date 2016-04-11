<?php

namespace App\Http\Controllers;

use App\OcpwStation;
use DB;

class OcpwController extends Controller
{
    public function listParameterTypes()
    {
        $parameters = $this->_getQueryBuilder()
                        ->select(DB::raw('parameter, type, count(*)'))
                        ->groupBy(DB::raw('parameter, type'))
                        ->get()
                        ->map(function($r){
                            return $r->toArray();
                        })
                        ->toArray();
        
        usort($parameters, function($a, $b){
            return $a['count'] < $b['count'] ? 1 : -1;
        });
        
        return view('parameters/list_parameter_types', [
            'parameters' => $parameters,
            'program' => $this->_getProgram()
        ]);
    }
    
    public function inspectParameterType()
    {
        $parameter = $this->getRouteParameter('parameter');
        $type = $this->getRouteParameter('type');
        
        $stats = $this->_computeParameterTypeStats($parameter, $type);
        
        $statsByDate = $this->_translateParameterTypeStatsToTimestamp($stats);
        
        return view('parameters/inspect_parameter_type', [
            'stats' => $stats,
            'statsByDate' => $statsByDate,
            'stations' => array_keys($stats),
            'program' => $this->_getProgram(),
            'parameter' => $parameter,
            'type' => $type
        ]);
    }
    
    protected function _getProgram()
    {
        return $this->getRouteParameter('program');
    }
    
    protected function _getQueryBuilder()
    {
        $className = '\App\Ocpw'.studly_case($this->_getProgram()).'Parameter';
        return $className::query();
    }
    
    protected function _getParameterTypeData($parameter, $type)
    {
        $data = [];
        
        $this->_getQueryBuilder()
            ->where('parameter', $parameter)
            ->where('type', $type)
            ->get()
            ->each(function($r) use (&$data) {
                if(!isset($data[$r->station]))
                    $data[$r->station] = [];
                if(!isset($data[$r->station][$r->date]))
                    $data[$r->station][$r->date] = [];
                $data[$r->station][$r->date][] = floatval($r->result);
            });
            
        foreach($data as $stationId => $stationData){
            foreach($stationData as $date => $values){
                $data[$stationId][$date] = array_sum($values) / count($values);
            }
        }
        
        return $data;
    }
    
    protected function _computeParameterTypeStats($parameter, $type)
    {
        $stats = [];
        
        foreach($this->_getParameterTypeData($parameter, $type) as $stationId => $stationData){
            
            $station = OcpwStation::where('stationcode', $stationId)->first();
            
            $rstat = new \RunningStat\RunningStat();
            foreach($stationData as $value)
                $rstat->addObservation($value);
            
            $stationStats = [
                'mean' => $station->getParameterMean($this->_getProgram(), $parameter, $type),
                'stddev' => $station->getParameterDeviation($this->_getProgram(), $parameter, $type),
                'data' => []
            ];
            
            foreach($stationData as $date => $value){
                $stationStats['data'][$date] = [
                    'value' => $value,
                    'mean' => $stationStats['mean'],
                    'dev' => $stationStats['stddev'] != 0 ? (($value - $stationStats['mean']) / $stationStats['stddev']) : 0
                ];
            }
            
            $stats[$stationId] = $stationStats;
        }
        
        return $stats;
    }
    
    protected function _translateParameterTypeStatsToTimestamp($stats)
    {
        $statsByDate = [];
        
        foreach($stats as $stationId => $stationStats){
            foreach($stationStats['data'] as $timestamp => $stationTimeStats){
                $timestamp = explode(' ', $timestamp)[0];
                if(!isset($statsByDate[$timestamp]))
                    $statsByDate[$timestamp] = [];
                $statsByDate[$timestamp][$stationId] = [
                    'value' => $stationTimeStats['value'],
                    'location_mean' => $stationTimeStats['mean'],
                    'location_dev' => $stationTimeStats['dev'],
                ];
            }
        }
        
        foreach($statsByDate as $timestamp => $datesStats){
            $rstat = new \RunningStat\RunningStat();
            foreach($datesStats as $stationId => $dateStats)
                $rstat->addObservation($dateStats['location_dev']);
            $meanOfLocationDevs = $rstat->getMean();
            $devOfLocationDevs = $rstat->getStdDev();
            foreach($datesStats as $stationId => $dateStats)
                $statsByDate[$timestamp][$stationId]['dev_of_location_devs'] = $devOfLocationDevs != 0 ? (($dateStats['location_dev'] - $meanOfLocationDevs) / $devOfLocationDevs) : 0;
        }
        
        ksort($statsByDate);
        
        return $statsByDate;
    }
}
