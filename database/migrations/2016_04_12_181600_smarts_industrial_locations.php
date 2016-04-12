<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SmartsIndustrialLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smarts_industrial_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('wdid');
            $table->string('site_facility_address');
            $table->string('site_facility_city');
            $table->string('site_facility_state');
            $table->string('site_facility_zip');
            $table->double('latitude', 15, 10)->nullable();
            $table->double('longitude', 15, 10)->nullable();
            $table->timestamps();
            
            $table->unique(['wdid', 'site_facility_address', 'site_facility_city', 'site_facility_state', 'site_facility_zip']);
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
        Schema::drop('smarts_industrial_locations');
    }
}
