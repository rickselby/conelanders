<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->unsignedInteger('driver_id')->change();
            $table->foreign('driver_id')->references('id')->on('drivers')
                ->onDelete('SET NULL');
        });
        Schema::table('drivers', function(Blueprint $table) {
            $table->unsignedInteger('nation_id')->change();
            $table->foreign('nation_id')->references('id')->on('nations')
                ->onDelete('RESTRICT');
        });
        Schema::table('points', function(Blueprint $table) {
            $table->unsignedInteger('points_sequence_id')->change();
            $table->foreign('points_sequence_id')->references('id')->on('points_sequences')
                ->onDelete('CASCADE');
        });
        Schema::table('ac_championship_entrants', function(Blueprint $table) {
            $table->unsignedInteger('ac_championship_id')->change();
            $table->unsignedInteger('driver_id')->change();
            $table->foreign('ac_championship_id')->references('id')->on('ac_championships')
                ->onDelete('CASCADE');
            $table->foreign('driver_id')->references('id')->on('drivers')
                ->onDelete('RESTRICT');
        });
        Schema::table('ac_events', function(Blueprint $table) {
            $table->unsignedInteger('ac_championship_id')->change();
            $table->foreign('ac_championship_id')->references('id')->on('ac_championships')
                ->onDelete('CASCADE');
        });
        Schema::table('ac_laptime_sectors', function(Blueprint $table) {
            $table->unsignedInteger('ac_laptime_id')->change();
            $table->foreign('ac_laptime_id')->references('id')->on('ac_laptimes')
                ->onDelete('CASCADE');
        });
        Schema::table('ac_sessions', function(Blueprint $table) {
            $table->unsignedInteger('ac_event_id')->change();
            $table->foreign('ac_event_id')->references('id')->on('ac_events')
                ->onDelete('CASCADE');
        });
        Schema::table('ac_session_entrants', function(Blueprint $table) {
            $table->unsignedInteger('ac_session_id')->change();
            $table->unsignedInteger('ac_championship_entrant_id')->change();
            $table->unsignedInteger('fastest_lap_id')->change();
            $table->foreign('ac_session_id')->references('id')->on('ac_sessions')
                ->onDelete('CASCADE');
            $table->foreign('ac_championship_entrant_id')->references('id')->on('ac_championship_entrants')
                ->onDelete('RESTRICT');
            $table->foreign('fastest_lap_id')->references('id')->on('ac_laptimes')
                ->onDelete('RESTRICT');
        });
        Schema::table('ac_session_laps', function(Blueprint $table) {
            $table->unsignedInteger('ac_session_entrant_id')->change();
            $table->unsignedInteger('ac_laptime_id')->change();
            $table->foreign('ac_session_entrant_id')->references('id')->on('ac_session_entrants')
                ->onDelete('CASCADE');
            $table->foreign('ac_laptime_id')->references('id')->on('ac_laptimes')
                ->onDelete('RESTRICT');
        });
        Schema::table('dirt_championships', function(Blueprint $table) {
            $table->unsignedInteger('event_points_sequence')->change();
            $table->unsignedInteger('stage_points_sequence')->change();
            $table->foreign('event_points_sequence')->references('id')->on('points_sequences')
                ->onDelete('RESTRICT');
            $table->foreign('stage_points_sequence')->references('id')->on('points_sequences')
                ->onDelete('RESTRICT');
        });
        Schema::table('dirt_events', function(Blueprint $table) {
            $table->unsignedInteger('dirt_season_id')->change();
            $table->foreign('dirt_season_id')->references('id')->on('dirt_seasons')
                ->onDelete('CASCADE');
        });
        Schema::table('dirt_event_positions', function(Blueprint $table) {
            $table->unsignedInteger('dirt_event_id')->change();
            $table->unsignedInteger('driver_id')->change();
            $table->foreign('dirt_event_id')->references('id')->on('dirt_events')
                ->onDelete('CASCADE');
            $table->foreign('driver_id')->references('id')->on('drivers')
                ->onDelete('RESTRICT');
        });
        Schema::table('dirt_results', function(Blueprint $table) {
            $table->unsignedInteger('dirt_stage_id')->change();
            $table->unsignedInteger('driver_id')->change();
            $table->foreign('dirt_stage_id')->references('id')->on('dirt_stages')
                ->onDelete('CASCADE');
            $table->foreign('driver_id')->references('id')->on('drivers')
                ->onDelete('RESTRICT');
        });
        Schema::table('dirt_seasons', function(Blueprint $table) {
            $table->unsignedInteger('dirt_championship_id')->change();
            $table->foreign('dirt_championship_id')->references('id')->on('dirt_championships')
                ->onDelete('CASCADE');
        });
        Schema::table('dirt_stages', function(Blueprint $table) {
            $table->unsignedInteger('dirt_event_id')->change();
            $table->foreign('dirt_event_id')->references('id')->on('dirt_events')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dirt_stages', function(Blueprint $table) {
            $table->dropForeign('dirt_stages_dirt_event_id_foreign');
        });
        Schema::table('dirt_seasons', function(Blueprint $table) {
            $table->dropForeign('dirt_seasons_dirt_championship_id_foreign');
        });
        Schema::table('dirt_results', function(Blueprint $table) {
            $table->dropForeign('dirt_results_dirt_stage_id_foreign');
            $table->dropForeign('dirt_results_driver_id_foreign');
        });
        Schema::table('dirt_event_positions', function(Blueprint $table) {
            $table->dropForeign('dirt_event_positions_dirt_event_id_foreign');
            $table->dropForeign('dirt_event_positions_driver_id_foreign');
        });
        Schema::table('dirt_events', function(Blueprint $table) {
            $table->dropForeign('dirt_events_dirt_season_id_foreign');
        });
        Schema::table('dirt_championships', function(Blueprint $table) {
            $table->dropForeign('dirt_championships_event_points_sequence_foreign');
            $table->dropForeign('dirt_championships_stage_points_sequence_foreign');
        });
        Schema::table('ac_session_laps', function(Blueprint $table) {
            $table->dropForeign('ac_session_laps_ac_session_entrant_id_foreign');
            $table->dropForeign('ac_session_laps_ac_laptime_id_foreign');
        });
        Schema::table('ac_session_entrants', function(Blueprint $table) {
            $table->dropForeign('ac_session_entrants_ac_session_id_foreign');
            $table->dropForeign('ac_session_entrants_ac_championship_entrant_id_foreign');
            $table->dropForeign('ac_session_entrants_fastest_lap_id_foreign');
        });
        Schema::table('ac_sessions', function(Blueprint $table) {
            $table->dropForeign('ac_sessions_ac_event_id_foreign');
        });
        Schema::table('ac_laptime_sectors', function(Blueprint $table) {
            $table->dropForeign('ac_laptime_sectors_ac_laptime_id_foreign');
        });
        Schema::table('ac_events', function(Blueprint $table) {
            $table->dropForeign('ac_events_ac_championship_id_foreign');
        });
        Schema::table('ac_championship_entrants', function(Blueprint $table) {
            $table->dropForeign('ac_championship_entrants_ac_championship_id_foreign');
            $table->dropForeign('ac_championship_entrants_driver_id_foreign');
        });
        Schema::table('points', function(Blueprint $table) {
            $table->dropForeign('points_points_sequence_id_foreign');
        });
        Schema::table('drivers', function(Blueprint $table) {
            $table->dropForeign('drivers_nation_id_foreign');
        });
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign('users_driver_id_foreign');
        });
    }
}
