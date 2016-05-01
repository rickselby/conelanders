<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NamePointsSequences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('points_sequences', function(Blueprint $table) {
            $table->string('name');
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
        Schema::table('points_sequences', function(Blueprint $table) {
            $table->dropColumn(['name', 'slug']);
        });
    }
}
