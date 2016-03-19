<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Createpositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('results', function(Blueprint $table) {
            $table->integer('position');
        });
        Schema::create('event_positions', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->index();
            $table->integer('driver_id')->index();
            $table->integer('position');
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
        Schema::table('results', function(Blueprint $table) {
            $table->dropColumn('position');
        });
        Schema::drop('event_positions');
    }
}
