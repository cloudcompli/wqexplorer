<?php

namespace App\Console\Commands\Import\SMARTS;

use App\SmartsViolation;
use Carbon\Carbon;
use CloudCompli\WQInvestigator\SMARTS\StormwaterViolations;
use Illuminate\Console\Command;
use PDOException;
use phpFastCache\CacheManager;
use Socrata;

class Violations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:smarts:violations {--token=} {--after=} {--before=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import parameters data from the SMARTS Stormwater Violations API';
    
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
        
        $this->info('Importing SMARTS Stormwater Violations between '.$after.' and '.$before);
        
        $socrata = new Socrata('https://greengov.data.ca.gov', $socrataToken);
        $smarts = new StormwaterViolations($socrata);
        
        $cache = CacheManager::Files([
            "storage" => "files",
            "path" => storage_path()."/app/cache",
        ]);
        $smarts->setCacheHandler($cache);
        
        $smarts->setOptions([
            'region_code' => '8',
            'after' => $after,
            'before' => $before,
            'violation_type' => [
                'Deficient BMP Implementation',
                'Unauthorized NSWD',
                'Unregulated Discharge',
                'Effluent',
                'Surface Water'
            ]
        ]);
        
        foreach($smarts->getViolationReports() as $data){
            
            if(isset($data['place_longitude'])){
                $data['longitude'] = $data['place_longitude'];
                unset($data['place_longitude']);
            }
            if(isset($data['place_latitude'])){
                $data['latitude'] = $data['place_latitude'];
                unset($data['place_latitude']);
            }

            if(isset($data['location_1'])){
                unset($data['location_1']);
            }
            
            $record = $this->newRecordWithData($data);
            
            $record->occurred_on = Carbon::parse($record->occurred_on);
            $record->effective_date = Carbon::parse($record->effective_date);
            
            if(isset($record->terminated_date))
                $record->terminated_date = Carbon::parse($record->terminated_date);
            
            $recordString = $record->facility_name
                                .' - '.$record->occurred_on
                                .' - '.$record->violation_type;
            
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
        $record = new SmartsViolation;
        foreach($data as $key => $value)
            $record->$key = $value;
        return $record;
    }
}