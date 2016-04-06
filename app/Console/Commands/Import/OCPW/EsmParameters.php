<?php

namespace App\Console\Commands\Import\OCPW;

use App\OcpwEsmParameter;

class EsmParameters extends Parameters
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:ocpw:esm {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Estuary & Watershed Monitoring parameters data from a OCPW CSV extract';
    
    /**
     * The class name of the model for the record.
     * 
     * @var string
     */
    protected $recordClass = OcpwEsmParameter::class;
}