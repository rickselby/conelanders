<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RxTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rx_cars', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('short_name');
            $table->timestamps();
        });

        Schema::create('rx_championships', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->unsignedTinyInteger('drop_events');
            $table->unsignedTinyInteger('constructors_count');
            $table->timestamps();
        });

        Schema::create('rx_championship_admins', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rx_championship_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->timestamps();

            $table->foreign('rx_championship_id')->references('id')->on('rx_championships')
                ->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('RESTRICT');
        });

        Schema::create('rx_events', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rx_championship_id')->index();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->dateTime('time');
            $table->dateTime('release')->nullable();
            $table->timestamps();

            $table->foreign('rx_championship_id')->references('id')->on('rx_championships')
                ->onDelete('CASCADE');
        });

        Schema::create('rx_event_entrants', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rx_event_id')->index();
            $table->unsignedInteger('driver_id')->index();
            $table->unsignedInteger('rx_car_id')->index();
            $table->timestamps();

            $table->foreign('rx_event_id')->references('id')->on('rx_events')
                ->onDelete('CASCADE');
            $table->foreign('driver_id')->references('id')->on('drivers')
                ->onDelete('RESTRICT');
            $table->foreign('rx_car_id')->references('id')->on('rx_cars')
                ->onDelete('RESTRICT');
        });

        Schema::create('rx_sessions', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rx_event_id')->index();
            $table->unsignedTinyInteger('order');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('heat');
            $table->boolean('show');
            $table->timestamps();

            $table->foreign('rx_event_id')->references('id')->on('rx_events')
                ->onDelete('CASCADE');
        });

        Schema::create('rx_session_entrants', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rx_session_id')->index();
            $table->unsignedInteger('rx_event_entrant_id')->index();
            $table->string('race')->nullable();
            $table->integer('time');
            $table->integer('lap');
            $table->boolean('dsq');
            $table->boolean('dnf');
            $table->integer('penalty');
            $table->integer('points');
            $table->integer('position');
            $table->timestamps();

            $table->foreign('rx_session_id')->references('id')->on('rx_sessions')
                ->onDelete('CASCADE');
            $table->foreign('rx_event_entrant_id')->references('id')->on('rx_event_entrants')
                ->onDelete('RESTRICT');
        });

        Schema::create('rx_heat_results', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rx_event_id')->index();
            $table->unsignedInteger('rx_event_entrant_id')->index();
            $table->integer('position');
            $table->integer('points');
            $table->timestamps();

            $table->foreign('rx_event_id')->references('id')->on('rx_events')
                ->onDelete('CASCADE');
            $table->foreign('rx_event_entrant_id')->references('id')->on('rx_event_entrants')
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
        Schema::drop('rx_heat_results');
        Schema::drop('rx_session_entrants');
        Schema::drop('rx_sessions');
        Schema::drop('rx_event_entrants');
        Schema::drop('rx_events');
        Schema::drop('rx_championship_admins');
        Schema::drop('rx_championships');
        Schema::drop('rx_cars');
    }
}
