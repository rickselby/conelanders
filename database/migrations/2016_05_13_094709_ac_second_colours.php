<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AcSecondColours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_championship_entrants', function(Blueprint $table) {
            $table->string('colour2')->nullable();
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
            $table->dropColumn('colour2');
        });
    }
}
