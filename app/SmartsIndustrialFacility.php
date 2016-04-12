<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SmartsIndustrialFacility
{
    protected $_fields = [
        'site_facility_name', 
        'site_facility_address',
        'site_facility_city',
        'site_facility_state',
        'site_facility_zip',
        'site_facility_county'
    ];
    
    protected $_wdid;
    
    protected $_loaded = false;
    
    protected $_data = [];
    
    protected $_parameters = [];
    
    public function __construct($wdidOrDataArray)
    {
        if($wdidOrDataArray instanceof Model){
            $wdidOrDataArray = $wdidOrDataArray->toArray();
        }
        
        if(is_array($wdidOrDataArray)){
            $this->_wdid = $wdidOrDataArray['wdid'];
            foreach($this->_fields as $field){
                if(isset($wdidOrDataArray[$field])){
                    $this->_data[$field] = $wdidOrDataArray[$field];
                }
            }
            $this->_loaded = true;
        }else{
            $this->_wdid = $wdidOrDataArray;
        }
    }
    
    public function __get($key)
    {
        if(!$this->_loaded){
            $this->load();
        }
        
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }
    
    public function __isset($key)
    {
        return isset($this->_data[$key]);
    }
    
    public function load()
    {
        $model = SmartsIndustrialParameter::where('wdid', $this->_wdid)->orderBy('date_time_of_sample_collection', 'desc')->first();
        foreach($this->_fields as $field){
            if(isset($model[$field])){
                $this->_data[$field] = $model[$field];
            }
        }
        $this->_loaded = true;
    }
    
    public function hasParameter($parameterName)
    {
        return $this->_parameters[$parameterName]->count() > 0;
    }
    
    public function hasLoadedParameter($parameterName)
    {
        return in_array($parameterName, $this->getLoadedParameters());
    }
    
    public function getLoadedParameters()
    {
        return array_keys($this->_parameters);
    }
    
    public function getParameterModels($parameterName)
    {
        if($this->hasLoadedParameter($parameterName)){
            return $this->_parameters[$parameterName];
        }else{
            return null;
        }
    }
    
    public function prepareLoadingForParameter($parameterName)
    {
        if(!isset($this->_parameters[$parameterName])){
            $this->_parameters[$parameterName] = new Collection();
        }
    }
    
    public function loadParameterModel(SmartsIndustrialParameter $parameter)
    {
        $this->prepareLoadingForParameter($parameter->parameter);
        $this->_parameters[$parameter->parameter]->push($parameter);
    }
    
    public static function allWithParameter($parameter)
    {
        if(!is_array($parameter))
            $parameter = [$parameter];
        
        $facilities = [];
        
        $query = SmartsIndustrialParameter::query()
            ->whereIn('parameter', $parameter)
            ->orderBy('date_time_of_sample_collection', 'desc')
            ->get()
            ->each(function($smartsIndustrialParemetersModel) use (&$facilities, &$parameter) {
                if(!isset($facilities[$smartsIndustrialParemetersModel->wdid])){
                    $facilities[$smartsIndustrialParemetersModel->wdid] = new SmartsIndustrialFacility($smartsIndustrialParemetersModel);
                    foreach($parameter as $p)
                        $facilities[$smartsIndustrialParemetersModel->wdid]->prepareLoadingForParameter($p);
                }
                $facilities[$smartsIndustrialParemetersModel->wdid]->loadParameterModel($smartsIndustrialParemetersModel);
            });
        
        return new Collection($facilities);
    }
}