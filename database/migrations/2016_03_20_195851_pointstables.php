<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pointstables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

        Schema::create('points_systems', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->integer('event_points_sequence')->index();
            $table->integer('stage_points_sequence')->index();
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
        Schema::drop('points');
        Schema::drop('points_sequences');
        Schema::drop('points_systems');
    }
}
