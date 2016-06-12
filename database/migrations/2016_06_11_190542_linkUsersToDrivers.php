<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LinkUsersToDrivers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('driver_id')->index();
            $table->boolean('driver_confirmed');
        });
        
        Schema::table('drivers', function (Blueprint $table) {
            $table->boolean('locked'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['driver_id', 'driver_confirmed']);
        });
        
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('locked');
        });
    }
}
