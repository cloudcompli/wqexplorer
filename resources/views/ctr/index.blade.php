<?php
use App\Ocpw;
use App\OcpwCtrLimits;

$seen = [];
$limits = array_filter(OcpwCtrLimits::getLimits(), function($a) use (&$seen) {
    $key = $a['ParameterCode'].'-'.$a['Fraction'].'-'.$a['WaterType'];
    if(in_array($key, $seen))
        return false;
    $seen[] = $key;
    return true;
});
usort($limits, function($a, $b){
    if($a['ParameterCode'] > $b['ParameterCode']){
        return 1;
    }elseif($a['ParameterCode'] < $b['ParameterCode']){
        return -1;
    }elseif($a['Fraction'] > $b['Fraction']){
        return 1;
    }elseif($a['Fraction'] < $b['Fraction']){
        return -1;
    }else{
        return 0;
    }
});

?>

@extends('layout')

@section('content')

        <h2>Mass Emissions</h2>
        <ul>
            @foreach($limits as $limit)
                @if($limit['WaterType'] == 'FW')
                <li>
                    <a href="{{ url('ctr/mass_emissions/'.$limit['ParameterCode'].'/'.$limit['WaterType'].'/'.$limit['Fraction']) }}">
                        {{ $limit['ParameterCode'] }} ({{ $limit['Fraction'] }})
                    </a>
                </li>
                @endif
            @endforeach
        </ul>
        
        <h2>Estuary/Wetlands</h2>
        <ul>
            @foreach($limits as $limit)
                @if($limit['WaterType'] == 'SW')
                <li>
                    <a href="{{ url('ctr/esm/'.$limit['ParameterCode'].'/'.$limit['WaterType'].'/'.$limit['Fraction']) }}">
                        {{ $limit['ParameterCode'] }} [ {{ $limit['Fraction'] }} ]
                    </a>
                </li>
                @endif
            @endforeach
        </ul>

    @foreach(Ocpw::$programs as $programKey => $programName)
    @endforeach

@stop