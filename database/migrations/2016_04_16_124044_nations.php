<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Nations extends Migration
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
            $table->integer('dirt_reference');
            $table->timestamps();
        });

        Schema::table('drivers', function(Blueprint $table) {
            $table->integer('nation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('points_systems', function(Blueprint $table) {
            $table->drop('nation_id');
        });
        Schema::drop('nations');
    }
}
