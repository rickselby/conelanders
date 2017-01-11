<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TeamChampionships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('races_championships', function(Blueprint $table) {
            $table->boolean('teams_group_by_size');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('races_championships', function(Blueprint $table) {
            $table->dropColumn(['teams_group_by_size']);
        });
    }
}
