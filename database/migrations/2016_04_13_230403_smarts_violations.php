<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SmartsViolations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smarts_violations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('regulated_facility_region');
            $table->string('place_address');
            $table->string('wdid')->nullable();
            $table->string('agency_type');
            $table->string('violation_type');
            $table->string('violation_source');
            $table->string('facility_name');
            $table->string('violation_id');
            $table->timestamp('occurred_on');
            $table->timestamp('terminated_date')->nullable();
            $table->string('priority');
            $table->string('violation_status');
            $table->string('npdes_serious');
            $table->string('place_zip')->nullable();
            $table->string('violation_exmpt');
            $table->string('place_city')->nullable();
            $table->string('status');
            $table->text('violation_description');
            $table->string('agency_name');
            $table->string('place_county')->nullable();
            $table->timestamp('effective_date');
            $table->string('reg_meas_id')->nullable();
            $table->string('reg_meas_type')->nullable();
            $table->double('latitude', 15, 10)->nullable();
            $table->double('longitude', 15, 10)->nullable();
            $table->timestamps();
            
            $table->unique('violation_id');
            $table->index('occurred_on');
            $table->index('effective_date');
            $table->index('facility_name');
            $table->index(['longitude', 'latitude']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('smarts_violations');
    }
}
