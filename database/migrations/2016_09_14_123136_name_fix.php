<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NameFix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_cars', function(Blueprint $table) {
            $table->renameColumn('name', 'short_name');
            $table->renameColumn('full_name', 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_cars', function(Blueprint $table) {
            $table->renameColumn('name', 'full_name');
            $table->renameColumn('short_name', 'name');
        });
    }
}
