<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SessionReleaseDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_events', function(Blueprint $table) {
            $table->dropColumn(['release']);
        });
        Schema::table('ac_sessions', function(Blueprint $table) {
            $table->dateTime('release')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_events', function(Blueprint $table) {
            $table->dateTime('release')->nullable();
        });
        Schema::table('ac_sessions', function(Blueprint $table) {
            $table->dropColumn(['release']);
        });
    }
}
