<?php

namespace App;

use Carbon\Carbon;

class Ocpw {
    
    public static $programs = [
        'esm' => 'Estuary/Wetlands', 
        'mass_emissions' => 'Mass Emissions', 
        'nsmp' => 'Non Storm Water'
    ];
    
    public static function getProgramParameterData($program, $parameter, $type, $after, $before)
    {
        $className = 'App\Ocpw'.studly_case($program).'Parameter';
        return $className::query()
                    ->inDateRange($after, $before)
                    ->where('parameter', $parameter)
                    ->where('type', $type)
                    ->get()
                    ->sort(function($a, $b){
                        return (new Carbon($a->date))->lte(new Carbon($b->date)) ? 1 : -1;
                    });
    }
}