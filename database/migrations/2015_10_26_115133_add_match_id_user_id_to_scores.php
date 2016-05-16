<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMatchIdUserIdToScores extends Migration
{
    public function up()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->integer('match_id')->after('stand_id')->unsigned();
            $table->integer('user_id')->after('match_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('match_id');
        });
    }
}
