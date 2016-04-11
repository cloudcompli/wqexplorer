<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OcpwStations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ocpw_stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('projectprogramcode');
            $table->string('stationcode');
            $table->string('stationdescription');
            $table->string('stationid');
            $table->decimal('latitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->string('abbreviation');
            $table->timestamps();
            
            $table->unique('stationcode');
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
        Schema::drop('ocpw_stations');
    }
}
