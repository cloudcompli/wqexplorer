<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OcpwEsmParameters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ocpw_esm_parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('program');
            $table->string('watershed');
            $table->string('station');
            $table->timestamp('date');
            $table->string('sample_type');
            $table->string('type');
            $table->string('matrixcode');
            $table->string('depth');
            $table->string('analysis');
            $table->string('parameter');
            $table->string('qualifier');
            $table->string('result');
            $table->string('units');
            $table->string('result_type');
            $table->string('composite_begin');
            $table->string('composite_end');
            $table->string('num_sample');
            $table->timestamps();
            
            $table->unique(['station','date','parameter','result']);
            $table->index(['parameter', 'result']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ocpw_esm_parameters');
    }
}
