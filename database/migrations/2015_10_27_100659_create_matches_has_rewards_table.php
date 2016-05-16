<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesHasRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reward_id')->unsigned();
            $table->integer('match_id')->unsigned();
            $table->integer('no_of_rewards');
            $table->datetime('expire');
            $table->datetime('start');
            $table->datetime('end');
            $table->integer('counter_pin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('match_rewards');
    }
}