@extends('layout')

@section('content')

<h2>{{ App\Ocpw::$programs[$program] }}</h2>

<table>
    <thead>
        <tr>
            <th>Parameter</th>
            <th>Type</th>
            <th>Count</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach($parameters as $parameter)
        <tr>
            <td>{{ $parameter['parameter'] }}</td>
            <td>{{ $parameter['type'] }}</td>
            <td>{{ $parameter['count'] }}</td>
            <td><a href="{{ url('ocpw/'.$program.'/'.$parameter['parameter'].'/'.$parameter['type']) }}">View</a></td>
        </tr>
    @endforeach
    </tbody>
</table>

@stop