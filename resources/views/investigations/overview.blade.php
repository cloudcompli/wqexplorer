<?php
use Carbon\Carbon; 
?>

@extends('layout')

@section('head')
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
<script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
<style>
#mapid { height: 400px; width: 800px; }
</style>
@stop

@section('content')

<h2>{{ $parameter }} - {{ Carbon::parse($investigationDate)->format('Y-m-d') }}</h2>

<div id="mapid"></div>
<?php $mapPoints = []; ?>

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
        $deviations = $stddev != 0 ? round(($record->result - $mean) / $stddev, 3) : 0;
        ?>
        <tr>
            <td>{{ $record->date }}</td>
            <td>{{ $record->station }} - {{ $record->stationModel->stationdescription }}</td>
            <td>{{ $record->result }}</td>
            <td>{{ round($mean, 3) }}</td>
            <td>{{ $deviations }}</td>
        </tr>
        <?php
        if($deviations > 1.5){
            $icon = 'MONITORING_SUPER_DANGER';
        }else if($deviations > 1){
            $icon = 'MONITORING_DANGER';
        }else if($deviations > 0.2){
            $icon = 'MONITORING_WARNING';
        }else{
            $icon = 'MONITORING';
        }
        $mapPoints[] = [
            'popup' => '<h4>'.$record->station
                        .'<br><small>'.$record->stationModel->stationdescription.'</small>'
                    .'</h4>'
                    .'<ul>'
                    .'<li><strong>Date:</strong> '.(new Carbon($record->date))->format('Y-m-d').'</li>'
                    .'<li><strong>Result:</strong> '.round($record->result, 3).' '.$record->units.'</li>'
                    .'<li><strong>Historical Mean:</strong> '.round($mean, 3).' '.$record->units.'</li>'
                    .'<li><strong>Deviations from Mean:</strong> '.$deviations.'</li>'
                    .'</ul>',
            'longitude' => $record->stationModel->longitude, 
            'latitude' => $record->stationModel->latitude,
            'icon' => $icon
        ];
        ?>
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
                    
                    <?php
                    if(count($data->results) > 0){
                        $max = max($data->results);
                        $date = array_keys($data->results, $max)[0];
                        $deviations = $data->stddev != 0 ? round((max($data->results) - $data->mean)/$data->stddev, 3) : 0;
                        
                        if($deviations > 1.5){
                            $icon = 'FACTORY_SUPER_DANGER';
                        }else if($deviations > 1){
                            $icon = 'FACTORY_DANGER';
                        }else if($deviations > 0.2){
                            $icon = 'FACTORY_WARNING';
                        }else{
                            $icon = 'FACTORY';
                        }
                        
                        $mapPoints[] = [
                            'popup' => '<h4>'.$data->facility->site_facility_name
                                        .'<br><small>'
                                            .$data->facility->site_facility_address
                                            .', '.$data->facility->site_facility_city
                                            .' '.$data->facility->site_facility_state
                                            .' '.$data->facility->site_facility_zip.'</small>'
                                    .'</h4>'
                                    .'<ul>'
                                    .'<li><strong>Date:</strong> '.$date.'</li>'
                                    .'<li><strong>Result:</strong> '.round($max, 3).' '.$data->units.'</li>'
                                    .'<li><strong>Historical Mean:</strong> '.round($data->mean, 3).' '.$data->units.'</li>'
                                    .'<li><strong>Deviations from Mean:</strong> '.$deviations.'</li>'
                                    .'</ul>',
                            'longitude' => $data->facility->longitude, 
                            'latitude' => $data->facility->latitude,
                            'icon' => $icon
                        ];
                    }
                    ?>
                    
                @endforeach
            </tbody>
        </table>
        
    @endif

@endforeach

<script type='text/javascript'>
(function(){
    
    var LeafIcon = L.Icon.extend({
            options: {
                iconSize:     [32, 37],
                iconAnchor:   [16, 37],
                popupAnchor:  [0, -37]
            }
        }),
        icons = {
            FACTORY: new LeafIcon({iconUrl: "{{ asset('packages/mapicons/factory.png') }}"}),
            FACTORY_WARNING: new LeafIcon({iconUrl: "{{ asset('packages/mapicons/factory_warning.png') }}"}),
            FACTORY_DANGER: new LeafIcon({iconUrl: "{{ asset('packages/mapicons/factory_danger.png') }}"}),
            FACTORY_SUPER_DANGER: new LeafIcon({iconUrl: "{{ asset('packages/mapicons/factory_super_danger.png') }}"}),
            MONITORING: new LeafIcon({iconUrl: "{{ asset('packages/mapicons/monitoring.png') }}"}),
            MONITORING_WARNING: new LeafIcon({iconUrl: "{{ asset('packages/mapicons/monitoring_warning.png') }}"}),
            MONITORING_DANGER: new LeafIcon({iconUrl: "{{ asset('packages/mapicons/monitoring_danger.png') }}"}),
            MONITORING_SUPER_DANGER: new LeafIcon({iconUrl: "{{ asset('packages/mapicons/monitoring_super_danger.png') }}"}),
        },
        map = L.map('mapid').setView([33.7, -117.9], 11);
    map.scrollWheelZoom.disable();
    L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    }).addTo(map);
    
    @foreach($mapPoints as $mapPoint)
        var marker = L.marker([
            "{{ $mapPoint['latitude'] }}",
            "{{ $mapPoint['longitude'] }}"
        ], {
            icon: icons["{!! $mapPoint['icon'] !!}"]
        }).bindPopup("{!! $mapPoint['popup'] !!}").addTo(map);
    @endforeach
})();
</script>

@stop