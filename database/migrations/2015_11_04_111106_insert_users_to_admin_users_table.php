<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertUsersToAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        $data = [

            'email' => 'admin@admin.com',
            'password' =>  bcrypt('password')

        ];

        DB::table('admin_users')->insert($data);
    }


}
