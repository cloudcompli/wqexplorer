@extends('layout')

@section('head')
<style>
    table td:nth-child(3n+2), table td:nth-child(3n+3){ border-right: 1px solid #ccc; }
    table td:nth-child(3n+3), td:nth-child(3n+4){ color: #777; }
    table td:nth-child(n+1){ text-align: center; }
</style>
@stop

@section('content')

<h2>{{ App\Ocpw::$programs[$program] }} - {{ $parameter }} [{{ $type }}]</h2>

<table>
    <thead>
        <tr>
            <th rowspan='2'>Timestamp</th>
            @foreach($stations as $station)
            <th colspan='3'>{{ $station }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($stations as $station)
            <th>val</th>
            <th>loc mean</th>
            <th>loc dev</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach(array_reverse($statsByDate) as $date => $stats)
        <tr>
            <td><a href="{{ url('variances/investigations/'.$parameter.'/'.$type.'/'.$date) }}">{{ $date }}</a></td>
            @foreach($stations as $station)
            <td>
                @if(isset($stats[$station]))
                {{ round($stats[$station]['value'], 2) }}
                @endif
            </td>
            <td>
                @if(isset($stats[$station]))
                {{ round($stats[$station]['location_mean'], 2) }}
                @endif
            </td>
            <td>
                @if(isset($stats[$station]))
                <?php 
                if($stats[$station]['location_dev'] > 1.5){ 
                    $class = 'text-super-danger';
                }elseif($stats[$station]['location_dev'] > 1){
                    $class = 'text-danger';
                }elseif($stats[$station]['location_dev'] > 0.2){
                    $class = 'text-warning';
                }else{
                    $class = '';
                }
                ?>
                <span class="{{ $class }}">
                {{ round($stats[$station]['location_dev'], 2) }}
                </span>
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>

<p><a href='{{ url('variances/ocpw/parameters/'.$program) }}'>Back to Parameter List</a></p>

@stop