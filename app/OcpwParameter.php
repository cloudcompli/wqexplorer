<?php

namespace App;

use App\OcpwStation;
use Illuminate\Database\Eloquent\Model;

abstract class OcpwParameter extends Model
{
    public function scopeInDateRange($query, $after, $before)
    {
        return $query->where('date', '>=', $after)->where('date', '<=', $before);
    }
    
    public function stationModel()
    {
        return $this->belongsTo(OcpwStation::class, 'station', 'stationcode');
    }
}