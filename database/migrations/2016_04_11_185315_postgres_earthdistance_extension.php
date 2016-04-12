<?php

use Illuminate\Database\Migrations\Migration;

class PostgresEarthdistanceExtension extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE EXTENSION earthdistance;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP EXTENSION earthdistance;');
    }
}
