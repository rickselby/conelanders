<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DirtStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dirt_stage_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('location_name');
            $table->string('stage_name');
            $table->integer('dnf_time');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        // Import the stage names into the table
        app(\App\Services\DirtRally\ImportDirt::class)->getStageNames();

        // Set up the stages table with a column for the foreign key
        Schema::table('dirt_stages', function (Blueprint $table) {
            $table->unsignedInteger('dirt_stage_info_id')->index();
        });

        // Update the stages table to use the foreign key
        DB::update('UPDATE dirt_stages s 
                    LEFT JOIN dirt_stage_info i 
                      ON s.name = REPLACE(i.stage_name, \' (S)\', \'\') 
                      OR s.name = REPLACE(i.stage_name, \' (L)\', \'\')
                    SET s.dirt_stage_info_id = i.id');

        Schema::table('dirt_stages', function (Blueprint $table) {
            // FIRST THING WE DO is add the foreign key; if it break here, there
            // are stages that cannot be matched automatically
            $table->foreign('dirt_stage_info_id')->references('id')->on('dirt_stages')
                ->onDelete('RESTRICT');

            $table->dropColumn(['name', 'long']);
            $table->string('time_of_day');
            $table->string('weather');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // er, no. sorry.
    }
}
