<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AssettoCorsaHotlaps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_hotlap_sessions', function(Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->date('start');
            $table->date('finish');
            $table->text('slug');
            $table->timestamps();
        });

        Schema::create('ac_hotlap_session_cars', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ac_hotlap_session_id');
            $table->unsignedInteger('races_car_id');
            $table->timestamps();

            $table->foreign('ac_hotlap_session_id')->references('id')->on('ac_hotlap_sessions')
                ->onDelete('CASCADE');
            $table->foreign('races_car_id')->references('id')->on('races_cars')
                ->onDelete('RESTRICT');
        });

        Schema::create('ac_hotlap_session_entrants', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ac_hotlap_session_id');
            $table->unsignedInteger('driver_id');
            $table->unsignedInteger('races_car_id');
            $table->integer('position');
            $table->integer('time');
            $table->text('sectors');
            $table->timestamps();

            $table->foreign('ac_hotlap_session_id')->references('id')->on('ac_hotlap_sessions')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ac_hotlap_session_entrant');
        Schema::drop('ac_hotlap_session_cars');
        Schema::drop('ac_hotlap_sessions');
    }
}
