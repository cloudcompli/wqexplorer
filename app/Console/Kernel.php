<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Geocode\SMARTS\Industrial::class,
        Commands\Import\CIWQS\EsmrParameters::class,
        Commands\Import\OCPW\EsmParameters::class,
        Commands\Import\OCPW\MassEmissionsParameters::class,
        Commands\Import\OCPW\NsmpParameters::class,
        Commands\Import\OCPW\Stations::class,
        Commands\Import\SMARTS\ConstructionParameters::class,
        Commands\Import\SMARTS\IndustrialParameters::class,
        Commands\Import\SMARTS\Violations::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
