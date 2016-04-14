<?php

namespace App;

class OcpwCtrLimits {
    
    protected static $limits = [
        [
            "ParameterCode" => "Ag","WaterType" => "FW","TestLength" => "AC","Fraction" => "Dissolved","a" => "0.85","b" => "0","c" => "1.72","d" => "-6.52","v" => "NA"
        ],[
            "ParameterCode" => "Ag","WaterType" => "SW","TestLength" => "AC","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "1.9"
        ],[
            "ParameterCode" => "Cd","WaterType" => "FW","TestLength" => "AC","Fraction" => "Dissolved","a" => "1.137","b" => "-0.042","c" => "1.128","d" => "-3.6867","v" => "NA"
        ],[
            "ParameterCode" => "Cd","WaterType" => "FW","TestLength" => "CR","Fraction" => "Dissolved","a" => "1.107","b" => "-0.042","c" => "0.7852","d" => "-2.715","v" => "NA"
        ],[
            "ParameterCode" => "Cd","WaterType" => "SW","TestLength" => "AC","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "42"
        ],[
            "ParameterCode" => "Cd","WaterType" => "SW","TestLength" => "CR","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "9.3"
        ],[
            "ParameterCode" => "Cr","WaterType" => "FW","TestLength" => "AC","Fraction" => "Dissolved","a" => "0.316","b" => "0","c" => "0.819","d" => "3.688","v" => "NA"
        ],[
            "ParameterCode" => "Cr","WaterType" => "FW","TestLength" => "CR","Fraction" => "Dissolved","a" => "0.86","b" => "0","c" => "0.819","d" => "1.561","v" => "NA"
        ],[
            "ParameterCode" => "Cr","WaterType" => "SW","TestLength" => "AC","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "1100"
        ],[
            "ParameterCode" => "Cr","WaterType" => "SW","TestLength" => "CR","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "50"
        ],[
            "ParameterCode" => "Cu","WaterType" => "FW","TestLength" => "AC","Fraction" => "Dissolved","a" => "0.96","b" => "0","c" => "0.9422","d" => "-1.7","v" => "NA"
        ],[
            "ParameterCode" => "Cu","WaterType" => "FW","TestLength" => "CR","Fraction" => "Dissolved","a" => "0.96","b" => "0","c" => "0.8545","d" => "-1.702","v" => "NA"
        ],[
            "ParameterCode" => "Cu","WaterType" => "SW","TestLength" => "AC","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "4.8"
        ],[
            "ParameterCode" => "Cu","WaterType" => "SW","TestLength" => "CR","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "3.1"
        ],[
            "ParameterCode" => "Ni","WaterType" => "FW","TestLength" => "AC","Fraction" => "Dissolved","a" => "0.998","b" => "0","c" => "0.846","d" => "2.255","v" => "NA"
        ],[
            "ParameterCode" => "Ni","WaterType" => "FW","TestLength" => "CR","Fraction" => "Dissolved","a" => "0.997","b" => "0","c" => "0.846","d" => "0.0584","v" => "NA"
        ],[
            "ParameterCode" => "Ni","WaterType" => "SW","TestLength" => "AC","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "74"
        ],[
            "ParameterCode" => "Ni","WaterType" => "SW","TestLength" => "CR","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "8.2"
        ],[
            "ParameterCode" => "Pb","WaterType" => "FW","TestLength" => "AC","Fraction" => "Dissolved","a" => "1.462","b" => "-0.146","c" => "1.273","d" => "-1.46","v" => "NA"
        ],[
            "ParameterCode" => "Pb","WaterType" => "FW","TestLength" => "CR","Fraction" => "Dissolved","a" => "1.462","b" => "-0.146","c" => "1.273","d" => "-4.705","v" => "NA"
        ],[
            "ParameterCode" => "Pb","WaterType" => "SW","TestLength" => "AC","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "210"
        ],[
            "ParameterCode" => "Pb","WaterType" => "SW","TestLength" => "CR","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "8.1"
        ],[
            "ParameterCode" => "Zn","WaterType" => "FW","TestLength" => "AC","Fraction" => "Dissolved","a" => "0.978","b" => "0","c" => "0.8473","d" => "0.884","v" => "NA"
        ],[
            "ParameterCode" => "Zn","WaterType" => "FW","TestLength" => "CR","Fraction" => "Dissolved","a" => "0.986","b" => "0","c" => "0.8473","d" => "0.884","v" => "NA"
        ],[
            "ParameterCode" => "Zn","WaterType" => "SW","TestLength" => "AC","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "90"
        ],[
            "ParameterCode" => "Zn","WaterType" => "SW","TestLength" => "CR","Fraction" => "Dissolved","a" => "NA","b" => "NA","c" => "NA","d" => "NA","v" => "81"
        ],[
            "ParameterCode" => "Ag","WaterType" => "FW","TestLength" => "AC","Fraction" => "Total","a" => "NA","b" => "NA","c" => "1.72","d" => "-6.52","v" => "NA"
        ],[
            "ParameterCode" => "Cd","WaterType" => "FW","TestLength" => "AC","Fraction" => "Total","a" => "NA","b" => "NA","c" => "1.128","d" => "-3.6867","v" => "NA"
        ],[
            "ParameterCode" => "Cd","WaterType" => "FW","TestLength" => "CR","Fraction" => "Total","a" => "NA","b" => "NA","c" => "0.7852","d" => "-2.715","v" => "NA"
        ],[
            "ParameterCode" => "Cr","WaterType" => "FW","TestLength" => "AC","Fraction" => "Total","a" => "NA","b" => "NA","c" => "0.819","d" => "3.688","v" => "NA"
        ],[
            "ParameterCode" => "Cr","WaterType" => "FW","TestLength" => "CR","Fraction" => "Total","a" => "NA","b" => "NA","c" => "0.819","d" => "1.561","v" => "NA"
        ],[
            "ParameterCode" => "Cu","WaterType" => "FW","TestLength" => "AC","Fraction" => "Total","a" => "NA","b" => "NA","c" => "0.9422","d" => "-1.7","v" => "NA"
        ],[
            "ParameterCode" => "Cu","WaterType" => "FW","TestLength" => "CR","Fraction" => "Total","a" => "NA","b" => "NA","c" => "0.8545","d" => "-1.702","v" => "NA"
        ],[
            "ParameterCode" => "Ni","WaterType" => "FW","TestLength" => "AC","Fraction" => "Total","a" => "NA","b" => "NA","c" => "0.846","d" => "2.255","v" => "NA"
        ],[
            "ParameterCode" => "Ni","WaterType" => "FW","TestLength" => "CR","Fraction" => "Total","a" => "NA","b" => "NA","c" => "0.846","d" => "0.0584","v" => "NA"
        ],[
            "ParameterCode" => "Pb","WaterType" => "FW","TestLength" => "AC","Fraction" => "Total","a" => "NA","b" => "NA","c" => "1.273","d" => "-1.46","v" => "NA"
        ],[
            "ParameterCode" => "Pb","WaterType" => "FW","TestLength" => "CR","Fraction" => "Total","a" => "NA","b" => "NA","c" => "1.273","d" => "-4.705","v" => "NA"
        ],[
            "ParameterCode" => "Zn","WaterType" => "FW","TestLength" => "AC","Fraction" => "Total","a" => "NA","b" => "NA","c" => "0.8473","d" => "0.884","v" => "NA"
        ],[
            "ParameterCode" => "Zn","WaterType" => "FW","TestLength" => "CR","Fraction" => "Total","a" => "NA","b" => "NA","c" => "0.8473","d" => "0.884","v" => "NA"
        ]
    ];
    
    public static function getLimits($parameters = [])
    {
        $matchedLimits = [];
        foreach(static::$limits as $limit){
            $matches = true;
            foreach($parameters as $k => $v){
                if($limit[$k] != $v){
                    $matches = false;
                }
            }
            if($matches){
                $matchedLimits[] = $limit;
            }
        }
        return $matchedLimits;
    }
}