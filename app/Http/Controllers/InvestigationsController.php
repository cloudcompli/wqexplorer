<?php

namespace App\Http\Controllers;

use App\Ocpw;
use App\Parameters;
use App\SmartsIndustrialFacility;
use Carbon\Carbon;
use DB;
use RunningStat\RunningStat;

class InvestigationsController extends Controller
{
    public function overview()
    {
        $parameter = $this->getRouteParameter('parameter');
        $type = $this->getRouteParameter('type');
        $date = Carbon::parse($this->getRouteParameter('date'));
        
        $ocpwProgramsData = [];
        foreach(array_keys(Ocpw::$programs) as $program)
            $ocpwProgramsData[$program] = $this->_averageStationProgramParameterData(Ocpw::getProgramParameterData($program, $parameter, $type, $date->copy()->subDays(5), $date->copy()->addDays(15)));
        
        if(isset(Parameters::$mapOcpwToSmarts[$parameter]))
            $smartsParameters = Parameters::$mapOcpwToSmarts[$parameter];
        else
            $smartsParameters = [];
        
        $industrialFacilities = SmartsIndustrialFacility::allWithParameter($smartsParameters);
        
        return view('investigations/overview', [
            'ocpwProgramsData' => $ocpwProgramsData,
            'industrialFacilitiesData' => $this->_makeIndustrialFacilitiesData($industrialFacilities, $smartsParameters, $date),
            'parameter' => $parameter,
            'investigationDate' => $date
        ]);
    }
    
    protected function _makeIndustrialFacilitiesData($industrialFacilities, $smartsParameters, $investigationDate)
    {
        $industrialFacilitiesData = array_fill_keys($smartsParameters, []);
        
        foreach($smartsParameters as $smartsParameter){
            foreach($industrialFacilities as $industrialFacility){
                
                $parameterResults = [];
                $rstat = new RunningStat();
                
                foreach($industrialFacility->getParameterModels($smartsParameter) as $parameterModel){
                    if($parameterModel instanceof DateTime)
                        $dateString = $parameterModel->date_time_of_sample_collection->format('Y-m-d');
                    else
                        $dateString = Carbon::parse($parameterModel->date_time_of_sample_collection)->format('Y-m-d');
                    if(!isset($parameterResults[$dateString]))
                        $parameterResults[$dateString] = [];
                    $parameterResults[$dateString][] = $parameterModel->result;
                    $rstat->addObservation($parameterModel->result);
                }
                
                foreach($parameterResults as $dateString => $results){
                    if(Carbon::parse($dateString)->between(Carbon::parse($investigationDate)->subDays(60), Carbon::parse($investigationDate)->addDays(15)))
                        $parameterResults[$dateString] = array_sum($results) / count($results);
                    else
                        unset($parameterResults[$dateString]);
                }
                
                if($rstat->getCount() > 0){
                    $industrialFacilitiesData[$smartsParameter][] = (object)[
                        'facility' => $industrialFacility,
                        'results' => $parameterResults,
                        'mean' => $rstat->getMean(),
                        'stddev' => $rstat->getStdDev(),
                        'count' => $rstat->getCount()
                    ];
                }
            }
        }
        
        return $industrialFacilitiesData;
    }
    
    protected function _averageStationProgramParameterData($records)
    {
        $data = [];
        foreach($records as $record){
            $key = $record->date.$record->station;
            if(!isset($raw[$key])){
                $data[$key] = (object)[
                    'date' => $record->date,
                    'type' => $record->type,
                    'stationModel' => $record->stationModel,
                    'station' => $record->station,
                    'models' => []
                ];
            }
            $data[$key]->models[] = $record;
        }
        
        foreach($data as $key => $record){
            $rstat = new RunningStat;
            foreach($record->models as $model)
                $rstat->addObservation($model->result);
            $data[$key]->result = $rstat->getMean();
        }
        
        return new \Illuminate\Support\Collection(array_values($data));
    }
}
