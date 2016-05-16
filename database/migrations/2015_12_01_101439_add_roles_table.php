<?php

use App\Role;
use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function() {

            DB::table('roles')->insert([
                    [
                        'name' => 'Super Admin',
                    ],
                    [
                        'name' => 'Administrator',
                    ],
                    [
                        'name' => 'Manager',
                    ],
                    [
                        'name' => 'Report Viewer',
                    ],
                    [
                        'name' => 'App Users',
                    ]
                ]
            );

            Schema::table('users', function (Blueprint $table) {
                $table->integer('role_id')->nullable()->index();
            });

            $app_user= Role::where('name', 'App Users')->first();

            foreach (User::all() as $user) {
                $user->role_id = $app_user->id;
                $user->save();
            }
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_id');
        });
    }
}
