<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DirtCars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dirt_cars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('short_name');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        // Set up the stages table with a column for the foreign key
        Schema::table('dirt_results', function (Blueprint $table) {
            $table->unsignedInteger('dirt_car_id')->nullable()->index();

            $table->foreign('dirt_car_id')->references('id')->on('dirt_cars')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dirt_results', function (Blueprint $table) {
            $table->dropForeign(['dirt_car_id']);
            $table->dropColumn(['dirt_car_id']);
        });

        Schema::drop('dirt_cars');
    }
}
