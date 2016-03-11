<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('events', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('season_id')->index();
            $table->string('name');
            $table->date('closes');
            $table->timestamps();
        });

        Schema::create('stages', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->index();
            $table->tinyInteger('order');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('drivers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('results', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('driver_id')->index();
            $table->integer('stage_id')->index();
            $table->integer('time');
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
        Schema::drop('seasons');
        Schema::drop('events');
        Schema::drop('stages');
        Schema::drop('drivers');
        Schema::drop('results');
    }
}
