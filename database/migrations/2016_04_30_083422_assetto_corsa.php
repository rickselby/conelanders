<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AssettoCorsa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drivers', function(Blueprint $table) {
            $table->string('ac_guid')->nullable();
        });

        Schema::create('ac_championships', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('ac_championship_entrants', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ac_championship_id')->index();
            $table->integer('driver_id')->index();
            $table->boolean('rookie');
            $table->string('number');
            $table->string('colour');
            $table->timestamps();
        });

        Schema::create('ac_rookies', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ac_championship_id')->index();
            $table->integer('driver_id')->index();
            $table->timestamps();
        });

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
            $table->timestamps();
        });

        Schema::create('ac_laptimes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('time');
            $table->timestamps();
        });

        Schema::create('ac_laptime_sectors', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ac_laptime_id')->index();
            $table->integer('sector');
            $table->integer('time');
            $table->timestamps();
        });

        Schema::create('ac_race_laps', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('ac_race_entrant_id')->index();
            $table->integer('ac_laptime_id')->index();
            $table->integer('time');
            $table->timestamps();
        });

        Schema::create('ac_points_systems', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->integer('race_points_sequence')->index();
            $table->integer('laps_points_sequence')->index();
            $table->boolean('default');
            $table->string('slug')->nullable();
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
        Schema::drop('ac_points_systems');
        Schema::drop('ac_race_laps');
        Schema::drop('ac_laptime_sectors');
        Schema::drop('ac_laptimes');
        Schema::drop('ac_race_entrants');
        Schema::drop('ac_races');
        Schema::drop('ac_rookies');
        Schema::drop('ac_championship_entrants');
        Schema::drop('ac_championships');

        Schema::table('drivers', function(Blueprint $table) {
            $table->dropColumn(['ac_guid']);
        });
    }
}
