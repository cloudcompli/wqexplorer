<?php

namespace App\Console\Commands\Import\Ocpw;

use App\OcpwStation;
use Carbon\Carbon;
use CloudCompli\WQInvestigator\Support\Dataset\CsvDataset;
use Illuminate\Console\Command;
use PDOException;

class Stations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:ocpw:stations {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import station data from a OCPW CSV extract';
    
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
        
        $dataset = new CsvDataset($filePath);
        
        foreach($dataset->getData() as $data){
            
            if(strlen($data['longitude']) == 0 || strlen($data['latitude']) == 0){
                unset($data['longitude']);
                unset($data['latitude']);
            }
            
            $record = $this->newRecordWithData($data);
            
            $recordString = $record->stationcode.' - '.$record->stationdescription;
            
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
    
    public function newRecordWithData($data)
    {
        $record = new OcpwStation;
        foreach($data as $key => $value)
            $record->$key = $value;
        return $record;
    }
}