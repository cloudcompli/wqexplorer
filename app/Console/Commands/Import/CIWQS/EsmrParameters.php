<?php

namespace App\Console\Commands\Import\CIWQS;

use App\CiwqsEsmrParameter;
use Carbon\Carbon;
use CloudCompli\WQInvestigator\CIWQS\ESMR;
use Illuminate\Console\Command;
use PDOException;
use phpFastCache\CacheManager;
use Socrata;

class EsmrParameters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:ciwqs:esmr {--token=} {--after=} {--before=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import parameters data from the CIWQS eSMR API';
    
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
        $after = $this->option('after');
        $before = $this->option('before');
        $socrataToken = $this->option('token');
        
        $saved = 0;
        $skipped = 0;
        
        $this->info('Importing CIWQS eSMRs between '.$after.' and '.$before);
        
        $socrata = new Socrata('https://greengov.data.ca.gov', $socrataToken);
        $esmr = new ESMR($socrata);
        
        $cache = CacheManager::Files([
            "storage" => "files",
            "path" => storage_path()."/app/cache",
        ]);
        $esmr->setCacheHandler($cache);
        
        $esmr->setOptions([
            'region_code' => '8',
            'after' => $after,
            'before' => $before
        ]);
        
        
        foreach($esmr->getData() as $data){
            
            unset($data['location']);
            
            $data['latitude'] = array_key_exists('latitude_decimal_degrees', $data) ? $data['latitude_decimal_degrees'] : null;
            unset($data['latitude_decimal_degrees']);
            
            $data['longitude'] = array_key_exists('longitude_decimal_degrees', $data) ? $data['longitude_decimal_degrees'] : null;
            unset($data['longitude_decimal_degrees']);
            
            $record = $this->newRecordWithData($data);
            
            $record->sample_date = Carbon::parse($record->sample_date);
            
            $recordString = $record->facility_name
                                .' - '.$record->sample_date
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
                }elseif(preg_match('/null value in column \"result\"/', $ex->getMessage())){
                    $this->line('[SKIPPED - NO RESULT] '.$recordString);
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
        $record = new CiwqsEsmrParameter;
        foreach($data as $key => $value)
            $record->$key = $value;
        return $record;
    }
}