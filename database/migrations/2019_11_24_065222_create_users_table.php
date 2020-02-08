<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->text('name');
            $table->text('email');
            $table->unsignedBigInteger('phone');
            $table->text('address');
            $table->string('blood_group');
            $table->enum('role',['admin','user'])->default('user');
            $table->dateTime('latest_donotion_date')->nullable();

            $table->longText('activation_token')->nullable();
            $table->boolean('active_status')->default(0);
            
            $table->longText('password');
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
        Schema::dropIfExists('users');
    }
}
