<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConelandersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nations', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('acronym');
            $table->integer('dirt_reference');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('drivers', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('nation_id')->index();
            $table->string('name');
            $table->string('dirt_racenet_driver_id')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('dirt_championships', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('dirt_seasons', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dirt_championship_id')->index();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('dirt_events', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dirt_season_id')->index();
            $table->string('name');
            $table->integer('racenet_event_id')->nullable();
            $table->dateTime('opens');
            $table->dateTime('closes');
            $table->dateTime('last_import')->nullable()->default(null);
            $table->boolean('importing')->default(false);
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('dirt_stages', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dirt_event_id')->index();
            $table->tinyInteger('order');
            $table->boolean('long');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('dirt_results', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dirt_stage_id')->index();
            $table->integer('driver_id')->index();
            $table->integer('position');
            $table->integer('time');
            $table->integer('behind')->nullable();
            $table->boolean('dnf');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dirt_event_positions', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dirt_event_id')->index();
            $table->integer('driver_id')->index();
            $table->integer('position');
            $table->timestamps();
        });

        Schema::create('points', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('points_sequence_id')->index();
            $table->integer('position');
            $table->integer('points');
            $table->timestamps();
        });

        Schema::create('points_sequences', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Schema::create('dirt_points_systems', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->integer('event_points_sequence')->index();
            $table->integer('stage_points_sequence')->index();
            $table->boolean('default');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        /** Modify Things **/

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('admin')->default(false);
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
            $table->dropColumn('admin');
        });

        Schema::drop('dirt_points_systems');
        Schema::drop('points_sequences');
        Schema::drop('points');
        Schema::drop('dirt_event_positions');
        Schema::drop('dirt_results');
        Schema::drop('dirt_stages');
        Schema::drop('dirt_events');
        Schema::drop('dirt_seasons');
        Schema::drop('dirt_championships');
        Schema::drop('drivers');
        Schema::drop('nations');
    }
}
