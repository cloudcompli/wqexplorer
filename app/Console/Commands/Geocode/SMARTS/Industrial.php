<?php

namespace App\Console\Commands\Geocode\SMARTS;

use App\SmartsIndustrialLocation;
use App\SmartsIndustrialParameter;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Console\Command;
use PDOException;

class Industrial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geocode:smarts:industrial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Geocode industrial facilities from SMARTS HTML table extract';
    
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
        $saved = 0;
        $skipped = 0;
        $errored = 0;
        
        $facilities = SmartsIndustrialParameter::select(DB::raw('wdid, site_facility_address, site_facility_city, site_facility_state, site_facility_zip'))->groupBy(DB::raw('wdid, site_facility_address, site_facility_city, site_facility_state, site_facility_zip'))->get();
        
        foreach($facilities as $facility){
            $address = $facility->site_facility_address
                            .', '.$facility->site_facility_city
                            .' '.$facility->site_facility_state
                            .' '.$facility->site_facility_zip;
            
            if(SmartsIndustrialLocation::where('wdid', $facility->wdid)->where('site_facility_address', $facility->site_facility_address)->where('site_facility_city', $facility->site_facility_city)->where('site_facility_state', $facility->site_facility_state)->where('site_facility_zip', $facility->site_facility_zip)->count() == 0){
                $geocode = $this->get_content('http://maps.google.com/maps/api/geocode/json?address='.str_replace(' ','+',$address).'&sensor=false');
                $output = json_decode($geocode);
                
                if(isset($output->results[0])){
                    
                    $latitude = $output->results[0]->geometry->location->lat;
                    $longitude = $output->results[0]->geometry->location->lng;

                    $industrialLocation = new SmartsIndustrialLocation;
                    $industrialLocation->wdid = $facility->wdid;
                    $industrialLocation->site_facility_address = $facility->site_facility_address;
                    $industrialLocation->site_facility_city = $facility->site_facility_city;
                    $industrialLocation->site_facility_state = $facility->site_facility_state;
                    $industrialLocation->site_facility_zip = $facility->site_facility_zip;
                    $industrialLocation->longitude = $longitude;
                    $industrialLocation->latitude = $latitude;
                    $industrialLocation->save();

                    $this->comment('[SAVED] '.$facility->wdid.' - '.$address.' ['.$latitude.', '.$longitude.']');
                    $saved++;
                }else{
                    $this->line('[SKIPPED - BAD RESPONSE] '.$facility->wdid.' - '.$address);
                    $errored++;
                }
            }else{
                $this->line('[SKIPPED - DUPLICATE] '.$facility->wdid.' - '.$address);
                $skipped++;
            }
            
        }
            
        $this->info('Saved '.$saved.', errored '.$errored.', and skipped '.$skipped);
    }
    
    function get_content($URL){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $URL);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
}
}