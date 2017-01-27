<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AcTimePenalties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('races_session_entrants', function(Blueprint $table) {
            $table->integer('time_penalty')->nullable();
            $table->text('time_penalty_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('races_session_entrants', function(Blueprint $table) {
            $table->dropColumn(['time_penalty', 'time_penalty_reason']);
        });
    }
}
