<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Schedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function(Blueprint $table) {
            $table->dateTime('opens');
            $table->boolean('complete')->default(false);
            // Remove nullable
            $table->dateTime('closes')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function(Blueprint $table) {
            $table->dropColumn(['opens', 'complete']);
            $table->dateTime('closes')->nullable()->change();
        });
        //
    }
}
