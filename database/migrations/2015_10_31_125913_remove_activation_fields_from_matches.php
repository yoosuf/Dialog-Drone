<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveActivationFieldsFromMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('has_droneapp');
            $table->dropColumn('has_selfie');
            $table->dropColumn('has_quiz');
            $table->dropColumn('has_shoutout');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->boolean('has_droneapp', 1)->after('description');
            $table->boolean('has_selfie', 1)->after('description');
            $table->boolean('has_quiz', 1)->after('description');
            $table->boolean('has_shoutout', 1)->after('description');
        });
    }
}
