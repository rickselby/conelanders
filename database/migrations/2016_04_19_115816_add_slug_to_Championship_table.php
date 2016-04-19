<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class AddSlugToChampionshipTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('championships', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });
        Schema::table('nations', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });
        Schema::table('seasons', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });
        Schema::table('stages', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('championships', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('nations', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('seasons', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('stages', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

}
