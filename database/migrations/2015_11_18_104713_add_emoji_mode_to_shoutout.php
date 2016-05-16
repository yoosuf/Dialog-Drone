<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmojiModeToShoutout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_shoutouts', function (Blueprint $table) {
            $table->enum('emoji', ['1', '2', '3', '4', '5']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_shoutouts', function (Blueprint $table) {
            $table->dropColumn('emoji');
        });
    }
}
