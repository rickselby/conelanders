<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RacesAdmins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adminables', function(Blueprint $table) {
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('adminable_id')->index();
            $table->string('adminable_type');
            $table->timestamps();
        });

        Schema::drop('rx_championship_admins');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('adminables');

        Schema::create('rx_championship_admins', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rx_championship_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->timestamps();

            $table->foreign('rx_championship_id')->references('id')->on('rx_championships')
                ->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('RESTRICT');
        });

    }
}
