<?php

namespace App\Console\Commands\Import\SMARTS;

use Carbon\Carbon;
use CloudCompli\WQInvestigator\SMARTS\ParameterDataset;
use Exception;
use Illuminate\Console\Command;
use PDOException;

class Parameters extends Command
{
    protected $recordClass;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filePath = base_path().'/'.$this->argument('file');
        $this->info('Importing: '.$filePath);
        $saved = 0;
        $skipped = 0;
        
        $dataset = new ParameterDataset($filePath);
        
        foreach($dataset->getData() as $data){
            
            $record = $this->newRecordWithData($data);
            
            $record->date_time_of_sample_collection = Carbon::parse($record->date_time_of_sample_collection);
            if($record->longitude == '')
                $record->longitude = null;
            if($record->latitude == '')
                $record->latitude = null;
            
            $recordString = $record->site_facility_name
                                .' - '.Carbon::parse($record->date_time_of_sample_collection)
                                .' - '.$record->parameter
                                .' - '.$record->result
                                .' '.$record->units;
            
            try {
                $record->save();
                $this->comment('[SAVED] '.$recordString);
                $saved++;
            } catch (PDOException $ex) {
                if(preg_match('/violates unique constraint/', $ex->getMessage())){
                    $this->line('[SKIPPED - DUPLICATE] '.$recordString);
                    $skipped++;
                }else{
                    throw $ex;
                }
            }
        }
        $this->info('Saved '.$saved.' and skipped '.$skipped);
    }
    
    public function getRecordClass()
    {
        if(isset($this->recordClass))
            return $this->recordClass;
        else
            throw new Exception('Record class must be defined by command');
    }
    
    public function newRecordWithData($data)
    {
        $class = $this->getRecordClass();
        $record = new $class;
        foreach($data as $key => $value)
            $record->$key = $value;
        return $record;
    }
}