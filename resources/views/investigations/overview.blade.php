@extends('layout')

@section('content')

<h2>{{ $parameter }} - {{ (new Carbon\Carbon($date))->format('Y-m-d') }}</h2>

@foreach($ocpwProgramsData as $program => $ocpwProgramData)
    <h3>{{ App\Ocpw::$programs[$program] }}</h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Date</th>
                <th rowspan="2">Station</th>
                <th rowspan="2">Parameter</th>
                <th colspan="2">Historical</th>
                <th rowspan="2">Deviation from Expected</th>
            </tr>
            <tr>
                <th>Mean</th>
                <th>Std. Dev</th>
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
            <td>{{ round($stddev, 3) }}</td>
            <td>{{ round(($record->result - $mean) / $stddev, 3) }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endforeach


@stop