<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAdminUsersToUsersTable extends Migration
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
                'login' => 'admin',
                'password' =>  bcrypt('password'),
                'is_admin' => 1
            ],
            [
                'login' => 'admin1',
                'password' =>  bcrypt('password'),
                'is_admin' => 1
            ]
        ];

        DB::table('users')->insert($data);
    }


}
