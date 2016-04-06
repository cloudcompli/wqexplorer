<?php

namespace App\Console\Commands\Import\SMARTS;

use App\SmartsIndustrialParameter;

class IndustrialParameters extends Parameters
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:smarts:industrial {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import raw industrial parameters data from a SMARTS HTML table extract';
    
    /**
     * The class name of the model for the record.
     * 
     * @var string
     */
    protected $recordClass = SmartsIndustrialParameter::class;
}