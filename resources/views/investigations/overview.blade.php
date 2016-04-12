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
            <td>{{ $stddev != 0 ? round(($record->result - $mean) / $stddev, 3) : 0 }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endforeach

<h2>Industrial Facilities</h2>

@foreach(array_keys($industrialFacilitiesData) as $parameter)

    @if(count($industrialFacilitiesData[$parameter]) > 0)

        <h3>{{ $parameter }}</h3>

        <table>
            <thead>
                <tr>
                    <th rowspan="2">Facility Name</th>
                    <th colspan="3">Expected</th>
                    <th colspan="3">Most Recent</th>
                </tr>
                <tr>
                    <th>Records</th>
                    <th>Mean</th>
                    <th>Std Dev</th>
                    <th>Date</th>
                    <th>Result</th>
                    <th>Deviations from Mean</th>
                </tr>
            </thead>
            <tbody>
                @foreach($industrialFacilitiesData[$parameter] as $data)

                    <?php 
                    $first = true; 
                    $numRows = count($data->results);
                    ?>

                    @foreach($data->results as $date => $result)
                        <?php
                        $deviations = $data->stddev != 0 ? round(($result - $data->mean) / $data->stddev, 3) : 0;
                        ?>
                        <tr>
                            @if($first)
                            <td rowspan="{{ $numRows }}">
                                {{ $data->facility->site_facility_name }}
                                <br>
                                <small>
                                {{ $data->facility->site_facility_address }},
                                {{ $data->facility->site_facility_city }}
                                {{ $data->facility->site_facility_state }}
                                {{ $data->facility->site_facility_zip }}
                                </small>
                            </td>
                            <td rowspan="{{ $numRows }}">{{ $data->count }}</td>
                            <td rowspan="{{ $numRows }}">{{ round($data->mean, 3) }}</td>
                            <td rowspan="{{ $numRows }}">{{ round($data->stddev, 3) }}</td>
                            @endif
                            <td>{{ $date }}</td>
                            <td>{{ round($result, 3) }}</td>
                            <td>
                                <span 
                                @if($deviations > 1.5)
                                class="text-super-danger"
                                @elseif($deviations > 1)
                                class="text-danger"
                                @elseif($deviations > 0.4)
                                class="text-warning"
                                @endif
                                >
                                {{ $deviations }}
                                </span>
                            </td>
                        </tr>
                        <?php $first = false; ?>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        
    @endif

@endforeach


@stop