<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(null);
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->boolean('blocked')->default(false);
            $table->unsignedBigInteger('user_role_id');
            $table->timestamps();

            $table->foreign('user_role_id')->references('id')->on('user_roles');

        });

        $userData = array(
            [
              'id' => 1,
              'name' => 'Admin',
              'email' => 'admin@gmail.com',
              'password' => Hash::make("123"),
              'blocked' => false,
              'user_role_id' => 1
            ],
        
            );
        DB::table('users')->insert($userData);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
