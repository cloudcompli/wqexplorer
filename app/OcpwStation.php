<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use RunningStat\RunningStat;

class OcpwStation extends Model
{
    public function getParameterResults($program, $parameter, $type)
    {
        $className = 'App\Ocpw'.studly_case($program).'Parameter';
        return $className::query()
                ->where('station', $this->stationcode)
                ->where('parameter', $parameter)
                ->where('type', $type)
                ->get();
    }
    
    public function getParameterMean($program, $parameter, $type)
    {
        $rstat = new RunningStat;
        foreach($this->getParameterResults($program, $parameter, $type) as $r){
            $rstat->addObservation($r->result);
        }
        return $rstat->getMean();
    }
    
    public function getParameterDeviation($program, $parameter, $type)
    {
        $rstat = new RunningStat;
        foreach($this->getParameterResults($program, $parameter, $type) as $r){
            $rstat->addObservation($r->result);
        }
        return $rstat->getStdDev();
    }
}