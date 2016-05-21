<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePointsSystems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dirt_championships', function(Blueprint $table) {
            $table->integer('event_points_sequence')->index();
            $table->integer('stage_points_sequence')->index();
        });

        Schema::drop('dirt_points_systems');

        Schema::table('ac_championships', function(Blueprint $table) {
            $table->integer('qual_points_sequence')->index();
            $table->integer('race_points_sequence')->index();
            $table->integer('laps_points_sequence')->index();
        });

        Schema::drop('ac_points_systems');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dirt_championships', function(Blueprint $table) {
            $table->dropColumn(['event_points_sequence', 'stage_points_sequence']);
        });
        Schema::table('ac_championships', function(Blueprint $table) {
            $table->dropColumn(['qual_points_sequence', 'race_points_sequence', 'laps_points_sequence']);
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
}
