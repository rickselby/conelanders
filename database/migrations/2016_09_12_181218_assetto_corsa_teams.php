<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AssettoCorsaTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_teams', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ac_championship_id');
            $table->string('name');
            $table->string('short_name');
            $table->unsignedInteger('ac_car_id')->nullable();
            $table->timestamps();

            $table->foreign('ac_championship_id')->references('id')->on('ac_championships')
                ->onDelete('RESTRICT');

            $table->foreign('ac_car_id')->references('id')->on('ac_cars')
                ->onDelete('RESTRICT');
        });

        Schema::table('ac_championship_entrants', function(Blueprint $table) {
            $table->unsignedInteger('ac_team_id')->nullable();
            $table->unsignedInteger('ac_car_id')->nullable();

            $table->foreign('ac_team_id')->references('id')->on('ac_teams')
                ->onDelete('RESTRICT');

            $table->foreign('ac_car_id')->references('id')->on('ac_cars')
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
        Schema::table('ac_championship_entrants', function(Blueprint $table) {
            $table->dropForeign('ac_championship_entrants_ac_team_id_foreign');
            $table->dropForeign('ac_championship_entrants_ac_car_id_foreign');
            $table->dropColumn('ac_team_id');
            $table->dropColumn('ac_car_id');
        });
        Schema::drop('ac_teams');
    }
}
