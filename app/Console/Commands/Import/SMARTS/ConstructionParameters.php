<?php

namespace App\Console\Commands\Import\SMARTS;

use App\SmartsConstructionParameter;

class ConstructionParameters extends Parameters
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:smarts:construction {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import raw construction parameters data from a SMARTS HTML table extract';
    
    /**
     * The class name of the model for the record.
     * 
     * @var string
     */
    protected $recordClass = SmartsConstructionParameter::class;
}