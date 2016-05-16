<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyYawFieldsInStands extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stands', function (Blueprint $table) {
            $table->dropColumn('yaw');
            $table->decimal('yaw_default', 12, 2)->nullable();
            $table->decimal('yaw_start', 12, 2)->nullable();
            $table->decimal('yaw_end', 12, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stands', function (Blueprint $table) {
            $table->decimal('yaw', 12, 2)->nullable();
            $table->dropColumn('yaw_default');
            $table->dropColumn('yaw_start');
            $table->dropColumn('yaw_end');
        });
    }
}
