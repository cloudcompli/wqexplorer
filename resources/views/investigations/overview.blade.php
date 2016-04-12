<?php
use Carbon\Carbon; 
?>

@extends('layout')

@section('content')

<h2>{{ $parameter }} - {{ Carbon::parse($investigationDate)->format('Y-m-d') }}</h2>

@foreach($ocpwProgramsData as $program => $ocpwProgramData)
    <h3>{{ App\Ocpw::$programs[$program] }}</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Station</th>
                <th>Result</th>
                <th>Mean</th>
                <th>Deviations from Mean</th>
            </tr>
        </thead>
        <tbody>
        @foreach($ocpwProgramData as $record)
        <?php
        $mean = $record->stationModel->getParameterMean($program, $parameter, $record->type);
        $stddev = $record->stationModel->getParameterDeviation($program, $parameter, $record->type);
        ?>
        <tr>
            <td>{{ $record->date }}</td>
            <td>{{ $record->station }} - {{ $record->stationModel->stationdescription }}</td>
            <td>{{ $record->result }}</td>
            <td>{{ round($mean, 3) }}</td>
            <td>{{ round(($record->result - $mean) / $stddev, 3) }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endforeach

<h2>Industrial Facilities</h2>

@foreach($smartsParameters as $parameter)

    <h3>{{ $parameter }} </h3>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Facility Name</th>
                <th colspan="2">Expected</th>
                <th colspan="3">Most Recent</th>
            </tr>
            <tr>
                <th>Mean</th>
                <th>Std Dev</th>
                <th>Date</th>
                <th>Result</th>
                <th>Deviations from Mean</th>
            </tr>
        </thead>
        <tbody>
            @foreach($industrialFacilities as $industrialFacility)
            
                <?php 
                
                $first = true; 
                $parameterModels = $industrialFacility->getParameterModels($parameter);
                
                $parameterResults = [];
                $rstat = new RunningStat\RunningStat();
                
                foreach($parameterModels as $parameterModel){
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
                    if(Carbon::parse($dateString)->between(Carbon::parse($investigationDate)->subDays(30), Carbon::parse($investigationDate)))
                        $parameterResults[$dateString] = array_sum($results) / count($results);
                    else
                        unset($parameterResults[$dateString]);
                }
                
                $numEntries = count($parameterResults);
                $mean = $rstat->getMean();
                $stddev = $rstat->getStdDev();
                
                ?>
                
                @foreach($parameterResults as $date => $parameterResult)
                    <tr>
                        @if($first)
                        <td rowspan="{{ $numEntries }}">{{ $industrialFacility->site_facility_name }}</td>
                        <td rowspan="{{ $numEntries }}">{{ round($mean, 3) }}</td>
                        <td rowspan="{{ $numEntries }}">{{ round($stddev, 3) }}</td>
                        @endif
                        <td>{{ $date }}</td>
                        <td>{{ round($parameterResult, 3) }}</td>
                        <td>{{ $stddev != 0 ? round(($parameterResult - $mean) / $stddev, 3) : 0 }}</td>
                    </tr>
                    <?php $first = false; ?>
                @endforeach
            @endforeach
        </tbody>
    </table>

@endforeach


@stop