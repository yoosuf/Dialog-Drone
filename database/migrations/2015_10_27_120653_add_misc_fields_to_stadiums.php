<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMiscFieldsToStadiums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stadiums', function (Blueprint $table) {
            $table->string('address')->after('name')->nullable();
            $table->decimal('lat', 9, 6)->after('address')->nullable();
            $table->decimal('lng', 9, 6)->after('lat')->nullable();
            $table->string('map_image', 400)->after('lng')->nullable();
            $table->string('icon_image', 400)->after('map_image')->nullable();
            $table->string('map_x')->after('icon_image')->nullable();
            $table->string('map_y')->after('map_x')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stadiums', function (Blueprint $table) {
            $table->dropColumn(['address', 'lat', 'lng', 'map_image', 'icon_image', 'map_x', 'map_y']);

        });
    }
}
