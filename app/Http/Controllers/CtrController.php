<?php

namespace App\Http\Controllers;

use App\OcpwCtrLimits;
use App\Parameters;
use App\SmartsIndustrialFacility;
use App\SmartsViolation;
use Carbon\Carbon;
use DB;
use RunningStat\RunningStat;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CtrController extends Controller
{
    public function index()
    {
        return view('ctr/index');
    }
    
    public function results()
    {
        $date = Carbon::parse($this->getRouteParameter('date'));
        
        $results = $this->_getParameterResultsFromRouteParametersQuery()->get()->toArray();
        
        if(count($this->_getLimitsFromRouteParameters()) == 0){
            throw new NotFoundHttpException;
        }
        
        $this->_computeParameterResultsLimits($results);
        
        return view('ctr/results', [
            'programCode' => $this->getRouteParameter('programCode'),
            'parameterCode' => $this->getRouteParameter('parameterCode'),
            'waterType' => $this->getRouteParameter('waterType'),
            'fraction' => $this->getRouteParameter('fraction'),
            'results' => $results
        ]);
    }
    
    public function investigate()
    {
        $parameter = $this->getRouteParameter('parameterCode');
        $date = Carbon::parse($this->getRouteParameter('date'));
        $results = $this->_getParameterResultsFromRouteParametersQuery()
                ->where($this->_getParameterResultsTableName().'.date', '>', $date->copy()->subDays(20))
                ->where($this->_getParameterResultsTableName().'.date', '<', $date->copy()->addDays(10))
                ->get()
                ->toArray();
        
        if(count($this->_getLimitsFromRouteParameters()) == 0){
            throw new NotFoundHttpException;
        }
        
        $this->_computeParameterResultsLimits($results);
        
        if(isset(Parameters::$mapOcpwToSmarts[$parameter]))
            $smartsParameters = Parameters::$mapOcpwToSmarts[$parameter];
        else
            $smartsParameters = [];
        
        $industrialFacilities = SmartsIndustrialFacility::allWithParameter($smartsParameters);
        
        $violations = SmartsViolation::where('effective_date', '>', $date->copy()->subDays(45))->where('effective_date', '<', $date->copy()->addDays(15))->orderBy('effective_date', 'desc')->get();
        
        return view('ctr/investigate', [
            'programCode' => $this->getRouteParameter('programCode'),
            'parameterCode' => $this->getRouteParameter('parameterCode'),
            'waterType' => $this->getRouteParameter('waterType'),
            'fraction' => $this->getRouteParameter('fraction'),
            'results' => $results,
            'industrialFacilitiesData' => $this->_makeIndustrialFacilitiesData($industrialFacilities, $smartsParameters, $date),
            'violations' => $violations,
            'parameter' => $parameter,
            'investigationDate' => $date
        ]);
    }
    
    protected function _computeParameterResultsLimits(&$results)
    {
        $limits = $this->_getLimitsFromRouteParameters();
        $waterType = $this->getRouteParameter('waterType');
        $fraction = $this->getRouteParameter('fraction');
        
        foreach($results as $idx => $result){
            
            foreach($limits as $limit){
                
                $limitVal = null;
                if($waterType == 'SW'){
                    $limitVal = (float)$limit['v'];
                }elseif($waterType == 'FW'){
                    $h = log((float)$result['hardness'], M_E);
                    $chd = (float)$limit['c'] * $h + (float)$limit['d']; //(c * h + d)
                    if($fraction == 'Dissolved'){
                        $abh = (float)$limit['a'] + (float)$limit['b'] * $h;
                        $limitVal = $abh * exp($chd); // (a + b * h) * exp(c * h + d)
                    }elseif($fraction == 'Total'){
                        $limitVal = exp($chd); // exp(c * h + d)
                    }
                }
                
                if(!is_null($limitVal))
                    $results[$idx]['limit_'.$limit['TestLength']] = $limitVal;
            }
        }
    }
    
    protected function _getLimitsFromRouteParameters()
    {
        return OcpwCtrLimits::getLimits([
            'ParameterCode' => $this->getRouteParameter('parameterCode'),
            'WaterType' => $this->getRouteParameter('waterType'),
            'Fraction' => $this->getRouteParameter('fraction'),
        ]);
    }
    
    protected function _getParameterResultsTableName()
    {
        $className = 'App\Ocpw'.studly_case($this->getRouteParameter('programCode')).'Parameter';
        return (new $className)->getTable();
    }
    
    protected function _getParameterResultsFromRouteParametersQuery()
    {
        
        $className = 'App\Ocpw'.studly_case($this->getRouteParameter('programCode')).'Parameter';
        $tableName = (new $className)->getTable();
        
        return $className::query()
                ->select(DB::raw($tableName.'.station, '.$tableName.'.date, '.$tableName.'.result, '.$tableName.'.units, hardness.date as hardness_date, hardness.result as hardness'))
                ->join(DB::raw($tableName.' as hardness'), function($join) use($tableName) {
                    $join->on($tableName.'.station', '=', 'hardness.station');
                    $join->on($tableName.'.date', '=', 'hardness.date');
                })
                ->where($tableName.'.parameter', $this->getRouteParameter('parameterCode'))
                ->where($tableName.'.matrixcode', $this->getRouteParameter('waterType'))
                ->where($tableName.'.type', 'LIKE', '%'.($this->getRouteParameter('fraction') == 'Dissolved' ? 'F' : 'T').'%')
                ->where('hardness.parameter', 'Hardness')
                ->orderBy('date', 'desc');
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
                        'units' => $parameterModel->units,
                        'mean' => $rstat->getMean(),
                        'stddev' => $rstat->getStdDev(),
                        'count' => $rstat->getCount()
                    ];
                }
            }
        }
        
        return $industrialFacilitiesData;
    }
}