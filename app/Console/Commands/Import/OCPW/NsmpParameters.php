<?php

namespace App\Console\Commands\Import\OCPW;

use App\OcpwNsmpParameter;

class NsmpParameters extends Parameters
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:ocpw:nsmp {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Non-Stormwater Management Program parameters data from a OCPW CSV extract';
    
    /**
     * The class name of the model for the record.
     * 
     * @var string
     */
    protected $recordClass = OcpwNsmpParameter::class;
}