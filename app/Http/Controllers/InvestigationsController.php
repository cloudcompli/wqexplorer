<?php

namespace App\Http\Controllers;

use DB;

class InvestigationsController extends Controller
{
    public function overview()
    {
        $parameter = $this->getRouteParameter('parameter');
        $date = $this->getRouteParameter('date');
        
        return view('investigations/overview', [
            'parameter' => $parameter,
            'date' => $date
        ]);
    }
}
