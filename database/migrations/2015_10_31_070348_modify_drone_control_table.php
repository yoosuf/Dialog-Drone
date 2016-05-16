<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDroneControlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drone_controls', function (Blueprint $table) {
            $table->integer('match_id')->after('id')->unsigned();
            $table->boolean('is_active')->after('match_id')->unsigned();
            $table->string('type')->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drone_controls', function (Blueprint $table) {
            $table->dropColumn('match_id');
            $table->dropColumn('is_active');
            $table->dropColumn('type');
        });
    }
}
