<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AcEventChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_events', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ac_championship_id')->index();
            $table->string('name');
            $table->string('slug');
            $table->dateTime('time');
            $table->dateTime('release')->nullable();
            $table->timestamps();
        });

        Schema::create('ac_sessions', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ac_event_id')->index();
            $table->smallInteger('order');
            $table->smallInteger('type');
            $table->string('name');
            $table->string('slug');
            $table->boolean('importing');
            $table->timestamps();
        });

        Schema::create('ac_session_entrants', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ac_session_id')->index();
            $table->integer('ac_championship_entrant_id')->index();
            $table->smallInteger('ballast');
            $table->string('car')->nullable();
            $table->integer('started')->nullable();
            $table->integer('position')->nullable();
            $table->integer('time')->nullable();
            $table->boolean('dsq')->default(false);
            $table->boolean('dnf')->default(false);
            $table->integer('fastest_lap_id')->nullable()->index();
            $table->integer('fastest_lap_position')->nullable();
            $table->integer('points')->nullable();
            $table->integer('fastest_lap_points')->nullable();
            $table->timestamps();
        });


        Schema::create('ac_session_laps', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ac_session_entrant_id')->index();
            $table->integer('ac_laptime_id')->index();
            $table->integer('time');
            $table->timestamps();
        });

        Schema::table('ac_championships', function(Blueprint $table) {
            $table->dropColumn(['qual_points_sequence', 'race_points_sequence', 'laps_points_sequence']);
        });

        Schema::drop('ac_races');
        Schema::drop('ac_race_entrants');
        Schema::drop('ac_race_laps');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ac_events');
        Schema::drop('ac_sessions');
        Schema::drop('ac_session_entrants');
        Schema::drop('ac_session_laps');

        Schema::create('ac_races', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ac_championship_id')->index();
            $table->string('name');
            $table->dateTime('time');
            $table->dateTime('release')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('qualifying_import');
            $table->boolean('race_import');
            $table->timestamps();
        });

        Schema::create('ac_race_entrants', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ac_race_id')->index();
            $table->integer('ac_championship_entrant_id')->index();
            $table->smallInteger('ballast');
            $table->string('car')->nullable();
            $table->integer('qualifying_position')->nullable();
            $table->integer('qualifying_lap_id')->nullable()->index();
            $table->integer('race_position')->nullable();
            $table->integer('race_time')->nullable();
            $table->integer('race_laps')->nullable();
            $table->integer('race_behind')->nullable();
            $table->integer('race_fastest_lap_position')->nullable();
            $table->integer('race_fastest_lap_id')->nullable()->index();
            $table->boolean('race_disqualified')->default(false);
            $table->boolean('race_retired')->default(false);
            $table->timestamps();
        });

        Schema::create('ac_race_laps', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ac_race_entrant_id')->index();
            $table->integer('ac_laptime_id')->index();
            $table->integer('time');
            $table->timestamps();
        });
        
        Schema::table('ac_championships', function(Blueprint $table) {
            $table->integer('qual_points_sequence')->index();
            $table->integer('race_points_sequence')->index();
            $table->integer('laps_points_sequence')->index();
        });

    }
}
