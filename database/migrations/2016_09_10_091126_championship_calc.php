<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChampionshipCalc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_championships', function(Blueprint $table) {
            $table->unsignedTinyInteger('constructors_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_championships', function(Blueprint $table) {
            $table->dropColumn('constructors_count');
        });
    }
}
