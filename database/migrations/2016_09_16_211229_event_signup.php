<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventSignup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_events', function(Blueprint $table) {
            $table->dateTime('signup_open')->nullable();
            $table->dateTime('signup_close')->nullable();
        });

        Schema::create('ac_event_signups', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ac_event_id');
            $table->unsignedInteger('ac_championship_entrant_id');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ac_event_signups');
        Schema::table('ac_events', function(Blueprint $table) {
            $table->dropColumn(['signup_open', 'signup_close']);
        });
    }
}
