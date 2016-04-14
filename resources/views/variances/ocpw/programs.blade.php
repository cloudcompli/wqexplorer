@extends('layout')

@section('content')

<h2>Select a Program</h2>

<ul>
    @foreach(App\Ocpw::$programs as $key => $name)
    <li><a href="{{ url('variances/ocpw/'.$key) }}">{{ $name }}</a></li>
    @endforeach
</ul>

@stop