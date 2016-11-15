<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FlexiChamps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Start by clearing out the foreign keys that will break
         */

        Schema::table('ac_session_laps', function(Blueprint $table) {
            $table->dropForeign(['ac_session_entrant_id']);
            $table->dropForeign(['ac_laptime_id']);
        });
        Schema::table('ac_session_entrants', function(Blueprint $table) {
            $table->dropForeign(['ac_session_id']);
            $table->dropForeign(['ac_championship_entrant_id']);
            $table->dropForeign(['fastest_lap_id']);
            $table->dropForeign(['ac_car_id']);
        });
        Schema::table('ac_sessions', function(Blueprint $table) {
            $table->dropForeign(['ac_event_id']);
        });
        Schema::table('ac_laptime_sectors', function(Blueprint $table) {
            $table->dropForeign(['ac_laptime_id']);
        });
        Schema::table('ac_events', function(Blueprint $table) {
            $table->dropForeign(['ac_championship_id']);
        });
        Schema::table('ac_championship_entrants', function(Blueprint $table) {
            $table->dropForeign(['ac_championship_id']);
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['ac_team_id']);
            $table->dropForeign(['ac_car_id']);
        });
        Schema::table('ac_teams', function(Blueprint $table) {
            $table->dropForeign(['ac_championship_id']);
        });

        /**
         * Next, rename the tables, and drop some...
         */
        Schema::rename('ac_cars', 'races_cars');
        Schema::rename('ac_championship_entrants', 'races_championship_entrants');
        Schema::rename('ac_championships', 'races_championships');
        Schema::rename('ac_events', 'races_events');
        Schema::rename('ac_event_signups', 'races_event_signups');
        Schema::rename('ac_laptimes', 'races_laps');
        Schema::rename('ac_laptime_sectors', 'races_lap_sectors');
        Schema::rename('ac_session_entrants', 'races_session_entrants');
        Schema::rename('ac_sessions', 'races_sessions');
        Schema::rename('ac_teams', 'races_teams');
        Schema::drop('ac_rookies');

        /**
         * Then, rename the foreign key columns (and make other modifications)
         */
        Schema::table('races_championship_entrants', function (Blueprint $table) {
            $table->renameColumn('ac_championship_id', 'races_championship_id');
            $table->renameColumn('ac_team_id', 'races_team_id');
            $table->renameColumn('ac_car_id', 'races_car_id');
        });

        Schema::table('races_events', function (Blueprint $table) {
            $table->renameColumn('ac_championship_id', 'races_championship_id');
        });

        Schema::table('races_event_signups', function (Blueprint $table) {
            $table->renameColumn('ac_event_id', 'races_event_id');
            $table->renameColumn('ac_championship_entrant_id', 'races_championship_entrant_id');
        });

        Schema::table('races_laps', function(Blueprint $table) {
            $table->unsignedInteger('races_session_entrant_id')->after('id');
            $table->integer('time_set')->after('time');
            $table->renameColumn('time', 'laptime');
        });

        Schema::table('races_lap_sectors', function (Blueprint $table) {
            $table->renameColumn('ac_laptime_id', 'races_lap_id');
        });

        // Here, we need to copy data from ac_session_laps to the newly renamed laps table
        DB::update('UPDATE races_laps l INNER JOIN ac_session_laps asl ON l.id = asl.ac_laptime_id
                    SET l.races_session_entrant_id = asl.ac_session_entrant_id,
                        l.time_set = asl.`time`');
        DB::update('DELETE FROM races_laps WHERE races_session_entrant_id = 0');
        DB::delete('DELETE ls.* FROM races_lap_sectors ls LEFT JOIN races_laps l ON l.id = ls.races_lap_id WHERE l.id IS NULL');
        Schema::drop('ac_session_laps');


        Schema::table('races_session_entrants', function (Blueprint $table) {
            $table->renameColumn('ac_session_id', 'races_session_id');
            $table->renameColumn('ac_championship_entrant_id', 'races_championship_entrant_id');
            $table->renameColumn('ac_car_id', 'races_car_id');
            // Add team ID at session level
            $table->unsignedInteger('races_team_id')->nullable();
        });

        Schema::table('races_sessions', function (Blueprint $table) {
            $table->renameColumn('ac_event_id', 'races_event_id');
        });

        Schema::table('races_teams', function (Blueprint $table) {
            $table->renameColumn('ac_championship_id', 'races_championship_id');
            $table->renameColumn('ac_car_id', 'races_car_id');
        });

        /**
         * Finally, add the foreign keys back in
         */

        Schema::table('races_championship_entrants', function(Blueprint $table) {
            $table->foreign('races_championship_id')->references('id')->on('races_championships')
                ->onDelete('CASCADE');
            $table->foreign('driver_id')->references('id')->on('drivers')
                ->onDelete('RESTRICT');
            $table->foreign('races_team_id')->references('id')->on('races_teams')
                ->onDelete('RESTRICT');
            $table->foreign('races_car_id')->references('id')->on('races_cars')
                ->onDelete('RESTRICT');
        });

        Schema::table('races_events', function(Blueprint $table) {
            $table->foreign('races_championship_id')->references('id')->on('races_championships')
                ->onDelete('CASCADE');
        });

        Schema::table('races_lap_sectors', function(Blueprint $table) {
            $table->foreign('races_lap_id')->references('id')->on('races_laps')
                ->onDelete('CASCADE');
        });

        Schema::table('races_sessions', function(Blueprint $table) {
            $table->foreign('races_event_id')->references('id')->on('races_events')
                ->onDelete('CASCADE');
        });

        Schema::table('races_session_entrants', function(Blueprint $table) {
            $table->foreign('races_session_id')->references('id')->on('races_sessions')
                ->onDelete('CASCADE');
            $table->foreign('races_championship_entrant_id')->references('id')->on('races_championship_entrants')
                ->onDelete('RESTRICT');
            $table->foreign('fastest_lap_id')->references('id')->on('races_laps')
                ->onDelete('RESTRICT');
            $table->foreign('races_car_id')->references('id')->on('races_cars')
                ->onDelete('RESTRICT');
            $table->foreign('races_team_id')->references('id')->on('races_teams')
                ->onDelete('RESTRICT');
        });

        Schema::table('races_teams', function(Blueprint $table) {
            $table->foreign('races_championship_id')->references('id')->on('races_championships')
                ->onDelete('CASCADE');
            $table->foreign('races_car_id')->references('id')->on('races_cars')
                ->onDelete('RESTRICT');
        });

        Schema::table('races_laps', function(Blueprint $table) {
            $table->foreign('races_session_entrant_id')->references('id')->on('races_session_entrants')
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
        // hah! best of luck with that.
    }
}
