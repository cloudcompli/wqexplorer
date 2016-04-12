<?php

namespace App;

class Parameters
{
    public static $ocpw = [
        'esm' => [
            'TKN'
        ],
        'mass_emissions' => [
            'TKN'
        ]
    ];
    
    public static $mapOcpwToSmarts = [
        'TKN' => [
            'Nitrogen, Total (as N)', 
            'Nitrogen, Total (as NO3)', 
            'Nitrite Plus Nitrate (as N)', 
            'Nitrite, Total (as N)', 
            'Nitrite, Total (as NO2)', 
            'Nitrogen, Total (as N)', 
            'Total Kjeldahl Nitrogen (TKN) (as N)'
        ]
    ];
}