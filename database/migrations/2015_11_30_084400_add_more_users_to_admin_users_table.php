<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddMoreUsersToAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        $data = [
            [
                'login' => 'dialogadmin',
                'password' =>  bcrypt('36995eg9Yw'),
                'is_admin' => 1
            ],
            [
                'login' => 'arimacadmin',
                'password' =>  bcrypt('Rr2mB6IMgN'),
                'is_admin' => 1
            ]
        ];

        DB::table('users')->insert($data);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
