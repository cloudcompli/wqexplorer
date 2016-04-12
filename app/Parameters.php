<?php

namespace App;

class Parameters
{
    public static $ocpw = [
        'esm' => [
            'TSS',
            'TKN',
            'TotalPhosphorusPO4',
            'OrthoPhosphateP',
            'AmmoniaN',
            'NitrateNitriteNO3',
            'As',
            'Hg',
            'Be',
            'Ni',
            'Cu',
            'Fe',
            'Ag',
            'Pb',
            'Se',
            'Zn',
            'Cr',
            'Tl',
            'Sb',
            'Pb',
            'Cd',
            'Fe'
        ],
        'mass_emissions' => [
            'TotalPhosphorusPO4',
            'AmmoniaN',
            'TKN',
            'NitrateNitriteNO3',
            'OrthoPhosphateP',
            'Pb',
            'As',
            'Cr',
            'Ag',
            'Cd',
            'Fe',
            'Se',
            'Hg',
            'Cu',
            'Zn',
            'Ni',
            'Fe'
        ],
        'nsmp' => [
            
        ]
    ];
    
    public static $mapOcpwToSmarts = [
        'TKN' => [
            "Ammonia, Total (as N)",
            "Ammonia, Unionized (as N)",
            'Nitrogen, Total (as N)', 
            'Nitrogen, Total (as NO3)', 
            'Nitrite Plus Nitrate (as N)', 
            'Nitrite, Total (as N)', 
            'Nitrite, Total (as NO2)', 
            'Nitrogen, Total (as N)', 
            'Total Kjeldahl Nitrogen (TKN) (as N)'
        ],
        'TSS' => [
            'Total Suspended Solids (TSS)'
        ],
        'TotalPhosphorusPO4' => [
            "Phosphate, Total (as P)",
            "Phosphate, Total (as PO4)",
            "Phosphorus, Total (as P)"
        ],
        'OrthoPhosphateP' => [
            "Phosphate, Total (as P)",
            "Phosphate, Total (as PO4)",
            "Phosphorus, Total (as P)"
        ],
        'AmmoniaN' => [
            "Ammonia, Total (as N)",
            "Ammonia, Unionized (as N)",
            'Nitrogen, Total (as N)', 
            'Nitrogen, Total (as NO3)', 
            'Nitrite Plus Nitrate (as N)', 
            'Nitrite, Total (as N)', 
            'Nitrite, Total (as NO2)', 
            'Nitrogen, Total (as N)', 
            'Total Kjeldahl Nitrogen (TKN) (as N)'
        ],
        'NitrateNitriteNO3' => [
            "Ammonia, Total (as N)",
            "Ammonia, Unionized (as N)",
            'Nitrogen, Total (as N)', 
            'Nitrogen, Total (as NO3)', 
            'Nitrite Plus Nitrate (as N)', 
            'Nitrite, Total (as N)', 
            'Nitrite, Total (as NO2)', 
            'Nitrogen, Total (as N)', 
            'Total Kjeldahl Nitrogen (TKN) (as N)'
        ],
        'As' => [
            "Arsenic, Dissolved",
            "Arsenic, Total",
            "Arsenic, Total Recoverable"
        ],
        'Hg' => [
            "Mercury, Dissolved",
            "Mercury, Total",
            "Mercury, Total Recoverable"
        ],
        'Be' => [
            "Beryllium, Dissolved",
            "Beryllium, Total",
            "Beryllium, Total Recoverable"
        ],
        'Ni' => [
            "Nickel, Dissolved",
            "Nickel, Total",
            "Nickel, Total Recoverable"
        ],
        'Cu' => [
            "Copper, Dissolved",
            "Copper, Total",
            "Copper, Total Recoverable"
        ],
        'Fe' => [
            "Iron, Dissolved",
            "Iron, Total",
            "Iron, Total Recoverable"
        ],
        'Ag' => [
            "Silver, Dissolved",
            "Silver, Total",
            "Silver, Total Recoverable"
        ],
        'Pb' => [
            "Lead, Dissolved",
            "Lead, Organic",
            "Lead, Total",
            "Lead, Total Recoverable"
        ],
        'Se' => [
            "Selenium, Dissolved",
            "Selenium, Total",
            "Selenium, Total Recoverable"
        ],
        'Zn' => [
            "Zinc, Dissolved",
            "Zinc, Total",
            "Zinc, Total Recoverable"
        ],
        'Cr' => [
            "Chromium (Total)",
            "Chromium (VI)",
            "Chromium, Dissolved",
            "Chromium, Total Recoverable"
        ],
        'Tl' => [
            "Thallium, Dissolved",
            "Thallium, Total",
            "Thallium, Total "
        ],
        'Sb' => [
            "Antimony, Dissolved",
            "Antimony, Total"
        ],
        'Cd' => [
            "Cadmium, Dissolved",
            "Cadmium, Total",
            "Cadmium, Total Recoverable"
        ]
    ];
}