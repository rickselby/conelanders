<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Eventimporting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function(Blueprint $table) {
            $table->dateTime('last_import')->nullable()->default(null);
            $table->boolean('importing')->default(false);
            $table->dropColumn('complete');
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
            $table->dropColumn(['last_import', 'importing']);
            $table->boolean('complete')->default(false);
        });
    }
}
