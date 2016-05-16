<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySubStatusAddVenueToMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE matches MODIFY COLUMN sub_status VARCHAR(10);');
        Schema::table('matches', function (Blueprint $table) {
            $table->string('venue', 600);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE matches MODIFY COLUMN sub_status TINYINT(1);');
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('venue');
        });
    }
}
