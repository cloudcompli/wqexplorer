<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CiwqsEsmrParameters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciwqs_esmr_parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_id');
            $table->string('document_title');
            $table->string('facility_name');
            $table->decimal('latitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->string('measu_type')->nullable();
            $table->string('meth_det_lim')->nullable();
            $table->string('min_level')->nullable();
            $table->string('mon_location')->nullable();
            $table->string('parameter');
            $table->string('place_id');
            $table->string('qual')->nullable();
            $table->string('reg_meas_id')->nullable();
            $table->string('region_code');
            $table->string('reporting_limit')->nullable();
            $table->string('result');
            $table->timestamp('sample_date');
            $table->string('units');
            $table->string('written_procedure_code')->nullable();
            $table->timestamps();
            
            $table->unique(['reg_meas_id','mon_location', 'sample_date','parameter','result']);
            $table->index(['parameter', 'result']);
            $table->index('sample_date');
            $table->index('facility_name');
            $table->index('place_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ciwqs_esmr_parameters');
    }
}
