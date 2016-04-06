<?php

namespace App\Console\Commands\Import\OCPW;

use App\OcpwMassEmissionsParameter;

class MassEmissionsParameters extends Parameters
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:ocpw:mass_emissions {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Mass Emissions parameters data from a OCPW CSV extract';
    
    /**
     * The class name of the model for the record.
     * 
     * @var string
     */
    protected $recordClass = OcpwMassEmissionsParameter::class;
}