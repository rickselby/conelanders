<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChampsImprovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('races_categories', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::table('races_championships', function (Blueprint $table) {
            $table->unsignedInteger('races_category_id')->index();
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->renameColumn('ac_guid', 'steam_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->renameColumn('steam_id', 'ac_guid');
        });

        Schema::table('races_championships', function (Blueprint $table) {
            $table->dropColumn('races_category_id');
        });

        Schema::drop('races_categories');

    }
}
