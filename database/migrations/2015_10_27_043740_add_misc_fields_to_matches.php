<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMiscFieldsToMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->tinyInteger('sub_status')->after('status')->nullable();
            $table->string('banner_image', 400)->after('sub_status')->nullable();
            $table->text('interview_url')->after('sub_status')->nullable();
            $table->text('live_url')->after('sub_status')->nullable();
            $table->text('description')->after('sub_status')->nullable();
            $table->boolean('has_droneapp', 1)->after('description');
            $table->boolean('has_selfie', 1)->after('description');
            $table->boolean('has_quiz', 1)->after('description');
            $table->boolean('has_shoutout', 1)->after('description');
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
            $table->dropColumn('sub_status');
            $table->dropColumn('banner_image');
            $table->dropColumn('interview_url');
            $table->dropColumn('live_url');
            $table->dropColumn('description');
            $table->dropColumn('has_droneapp');
            $table->dropColumn('has_selfie');
            $table->dropColumn('has_quiz');
            $table->dropColumn('has_shoutout');
        });
    }
}
