<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftdeleteToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banner_images', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('drone_controls', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('helps', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('match_rewards', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_answers', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('rewards', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_selfies', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_shoutouts', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('stadiums', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('stands', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('match_teams', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('team_players', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('team_player_votes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_rewards', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banner_images', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('drone_controls', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('helps', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('match_rewards', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('user_answers', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('rewards', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('user_selfies', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('user_shoutouts', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('stadiums', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('stands', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('match_teams', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('team_players', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('team_player_votes', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('user_rewards', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
}
