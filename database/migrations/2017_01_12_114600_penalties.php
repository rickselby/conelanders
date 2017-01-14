<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Penalties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('races_penalties', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('races_session_entrant_id')->index();
            $table->integer('points');
            $table->text('reason');
            $table->timestamps();

            $table->foreign('races_session_entrant_id')->references('id')->on('races_session_entrants')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('races_penalties');
    }
}
