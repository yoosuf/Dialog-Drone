<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drone_control_activations', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('is_active')->default(0);
            $table->integer('drones_control_id')->unsigned();
            $table->integer('match_id')->unsigned();
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
        Schema::drop('drone_control_activations');
    }
}
