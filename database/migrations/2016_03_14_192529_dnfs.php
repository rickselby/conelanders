<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Dnfs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stages', function(Blueprint $table) {
            $table->boolean('long');
        });

        Schema::table('results', function(Blueprint $table) {
            $table->boolean('dnf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stages', function (Blueprint $table) {
            $table->dropColumn('long');
        });

        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn('dnf');
        });
    }
}
