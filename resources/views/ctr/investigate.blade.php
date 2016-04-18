<?php
use Carbon\Carbon; 
use App\OcpwStation;
?>

@extends('layout')

@section('head')
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
<script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
<link rel="stylesheet" href="http://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.css" />
<link rel="stylesheet" href="http://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.Default.css" />
<script src="http://leaflet.github.io/Leaflet.markercluster/dist/leaflet.markercluster-src.js"></script>
<style>
    .ctr_params td:first-child { white-space: nowrap; }
    .ctr_params td:nth-child(5n+3), td:nth-child(5n+5)  { border-right-color: #eee; }
    .ctr_params td:nth-child(5n+2), td:nth-child(5n+4)  { border-right-color: #bbb; }
    .ctr_params tbody > :first-child td { border-top-width: 2px; }
    .ctr_params td:nth-child(5n+1) { border-right-width: 2px; }
    #mapid { height: 400px; width: 800px; }
</style>
@stop

@section('content')

<h2>{{ $parameter }} - {{ Carbon::parse($investigationDate)->format('Y-m-d') }}</h2>

<div id="mapid"></div>
<?php $mapPoints = []; ?>

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

<h2>Sampling Results</h2>

<table class="ctr_params">
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
            
            <?php
            if(isset($dateResults[$station]['result'])){
                
                $max = 0;
                
                if(isset($dateResults[$station]['limit_AC']) && isset($dateResults[$station]['result'])){
                    if($dateResults[$station]['result']/$dateResults[$station]['limit_AC'] > $max){
                        $max = $dateResults[$station]['result']/$dateResults[$station]['limit_AC'];
                    }
                }
                
                if(isset($dateResults[$station]['limit_CR']) && isset($dateResults[$station]['result'])){
                    if($dateResults[$station]['result']/$dateResults[$station]['limit_CR'] > $max){
                        $max = $dateResults[$station]['result']/$dateResults[$station]['limit_CR'];
                    }
                }
                
                if($max >= 2){
                    $icon = 'MONITORING_SUPER_DANGER';
                }else if($max >= 1.5){
                    $icon = 'MONITORING_DANGER';
                }else if($max >= 1){
                    $icon = 'MONITORING_WARNING';
                }else{
                    $icon = 'MONITORING';
                }
                
                $stationModel = OcpwStation::where('stationcode', $station)->first();
                
                if($stationModel)
                    $popup = '<h4>'.$station
                                    .'<br><small>'.$stationModel->stationdescription.'</small>'
                                .'</h4>'
                                .'<ul>'
                                .'<li><strong>Date:</strong> '.(new Carbon($date))->format('Y-m-d').'</li>'
                                .'<li><strong>Result:</strong> '.round($dateResults[$station]['result'], 3).' '.$dateResults[$station]['units'].'</li>';
                
                    if(isset($dateResults[$station]['limit_AC'])){
                        $popup .= '<li><strong>Acute Limit:</strong> '.round($dateResults[$station]['limit_AC'],2).' '.$dateResults[$station]['units'].' ('.round($dateResults[$station]['result']/$dateResults[$station]['limit_AC']*100).'%)</li>';
                    }

                    if(isset($dateResults[$station]['limit_CR'])){
                        $popup .= '<li><strong>Chronic Limit:</strong> '.round($dateResults[$station]['limit_CR'],2).' '.$dateResults[$station]['units'].' ('.round($dateResults[$station]['result']/$dateResults[$station]['limit_CR']*100).'%)</li>';
                    }
                
                    $popup .= '</ul>';
                    
                    $mapPoints[] = [
                        'popup' => $popup,
                        'longitude' => $stationModel->longitude, 
                        'latitude' => $stationModel->latitude,
                        'icon' => $icon
                    ];
                
            }
            ?>
            
            
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>

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

<h3>Recent Violations</h3>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Facility</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        @foreach($violations as $violation)
        <?php
        $mapPoints[] = [
            'popup' => '<h4>'.$violation->facility_name
                        .'<br><small>'
                            .$violation->place_address
                            .', '.$violation->place_city
                            .' California'
                            .' '.$violation->place_zip.'</small>'
                    .'</h4>'
                    .'<ul>'
                    .'<li><strong>Date:</strong> '.Carbon::parse($violation->effective_date)->format('Y-m-d').'</li>'
                    .'<li><strong>Priority:</strong> '.$violation->priority.'</li>'
                    .'<li><strong>Status:</strong> '.$violation->violation_status.'</li>'
                    .'<li><strong>Description:</strong> '.htmlentities($violation->violation_description).'</li>'
                    .'</ul>',
            'longitude' => $violation->longitude, 
            'latitude' => $violation->latitude,
            'icon' => 'VIOLATION'
        ];
        ?>
        <tr>
            <td>{{ Carbon::parse($violation->effective_date)->format('Y-m-d') }}</td>
            <td>{{ $violation->facility_name }}
                <br><small>{{ $violation->place_address }}, {{ $violation->place_city }} California {{ $violation->place_zip }}</small></td>
            <td>{{ $violation->violation_description }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

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
            VIOLATION: new LeafIcon({iconUrl: "{{ asset('packages/mapicons/violation.png') }}"}),
        },
        map = L.map('mapid').setView([33.7, -117.9], 11);
    map.scrollWheelZoom.disable();
    L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    }).addTo(map);
    
    var markers = L.markerClusterGroup({
        maxClusterRadius: 1,
        zoomToBoundsOnClick: false
    });
    
    @foreach($mapPoints as $mapPoint)
        @if(is_numeric($mapPoint['latitude']) && is_numeric($mapPoint['longitude']))
        markers.addLayer(L.marker([
            "{{ $mapPoint['latitude'] }}",
            "{{ $mapPoint['longitude'] }}"
        ], {
            icon: icons["{!! $mapPoint['icon'] !!}"]
        }).bindPopup("{!! $mapPoint['popup'] !!}"));
        @endif
    @endforeach
    
    markers.addTo(map);
    
    markers.on('clusterclick', function (a) {
        a.layer.spiderfy();
    });
})();
</script>

@stop