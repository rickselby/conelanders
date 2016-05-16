<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AcDisqualifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_race_entrants', function(Blueprint $table) {
            $table->boolean('race_disqualified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_race_entrants', function(Blueprint $table) {
            $table->dropColumn('race_disqualified');
        });
    }
}
