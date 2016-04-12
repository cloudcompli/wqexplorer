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
            'smartsParameters' => $smartsParameters,
            'industrialFacilities' => $industrialFacilities,
            'parameter' => $parameter,
            'investigationDate' => $date
        ]);
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
