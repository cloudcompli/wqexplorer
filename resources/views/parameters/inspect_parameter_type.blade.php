@extends('layout')

@section('head')
<style>
    table td:nth-child(4n+2), table td:nth-child(4n+3), table td:nth-child(4n+4){ border-right: 1px solid #ccc; }
    table td:nth-child(4n+3), td:nth-child(4n+4), table td:nth-child(4n+5){ color: #777; }
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
            <th colspan='4'>{{ $station }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($stations as $station)
            <th>val</th>
            <th>loc mean</th>
            <th>loc dev</th>
            <th>time dev of loc devs</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach(array_reverse($statsByDate) as $date => $stats)
        <tr>
            <td><a href="{{ url('investigations/'.$parameter.'/'.$type.'/'.$date) }}">{{ $date }}</a></td>
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
                {{ round($stats[$station]['location_dev'], 2) }}
                @endif
            </td>
            <td>
                <span 
                @if(isset($stats[$station]))
                @if($stats[$station]['dev_of_location_devs'] > 1.5)
                class="text-super-danger"
                @elseif($stats[$station]['dev_of_location_devs'] > 1)
                class="text-danger"
                @elseif($stats[$station]['dev_of_location_devs'] > 0.4)
                class="text-warning"
                @endif
                >
                {{ round($stats[$station]['dev_of_location_devs'], 2) }}
                </span>
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>

<p><a href='{{ url('parameters/'.$program) }}'>Back to Parameter List</a></p>

@stop