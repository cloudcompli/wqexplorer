<?php
use Carbon\Carbon; 
?>

@extends('layout')

@section('head')
<style>
    td:first-child { white-space: nowrap; }
    td:nth-child(5n+3), td:nth-child(5n+5)  { border-right-color: #eee; }
    td:nth-child(5n+2), td:nth-child(5n+4)  { border-right-color: #bbb; }
    tbody > :first-child td { border-top-width: 2px; }
    td:nth-child(5n+1) { border-right-width: 2px; }
</style>
@stop

@section('content')

    <?php
    
    $stations = [];
    $resultsByDate = [];
    foreach($results as $result){
        
        if(!in_array($result['station'], $stations))
            $stations[] = $result['station'];
        
        $date = Carbon::parse($result['date'])->format('Y-m-d');
        
        if(!isset($resultsByDate[$date]))
            $resultsByDate[$date] = [];
        $resultsByDate[$date][$result['station']] = $result;
        
    }
    
    ?>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Date</th>
                @foreach($stations as $station)
                <th colspan="5">{{ $station }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($stations as $station)
                <th>Result</th>
                <th colspan='2'>Acute</th>
                <th colspan='2'>Chronic</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($resultsByDate as $date => $dateResults)
            <tr>
                <td>
                    <a href="{{ url('ctr/'.$programCode.'/'.$parameterCode.'/'.$waterType.'/'.$fraction.'/'.$date) }}">
                    {{ $date }}
                    </a>
                </td>
                @foreach($stations as $station)
                <td>
                    @if(isset($dateResults[$station]['result']))
                    {{ $dateResults[$station]['result'] }}
                    @endif
                </td>
                <td>
                    @if(isset($dateResults[$station]['limit_AC']))
                    {{ round($dateResults[$station]['limit_AC'], 2) }}
                    @endif
                </td>
                <td>
                    @if(isset($dateResults[$station]['limit_AC']) && isset($dateResults[$station]['result']))
                    <?php $percent = $dateResults[$station]['result']/$dateResults[$station]['limit_AC']; ?>
                    <span<?php
                    if($percent >= 2){
                        echo ' class="text-super-danger"';
                    }elseif($percent >= 1.5){
                        echo ' class="text-danger"';
                    }elseif($percent >= 1.0){
                        echo ' class="text-warning"';
                    }
                    ?>>
                    {{ round($percent*100) }}%
                    </span>
                    @endif
                </td>
                <td>
                    @if(isset($dateResults[$station]['limit_CR']))
                    {{ round($dateResults[$station]['limit_CR'], 2) }}
                    @endif
                </td>
                <td>
                    @if(isset($dateResults[$station]['limit_CR']) && isset($dateResults[$station]['result']))
                    <?php $percent = $dateResults[$station]['result']/$dateResults[$station]['limit_CR']; ?>
                    <span<?php
                    if($percent >= 2){
                        echo ' class="text-super-danger"';
                    }elseif($percent >= 1.5){
                        echo ' class="text-danger"';
                    }elseif($percent >= 1.0){
                        echo ' class="text-warning"';
                    }
                    ?>>
                    {{ round($percent*100) }}%
                    </span>
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

@stop