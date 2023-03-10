<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',50);
            $table->string('email',50)->unique()->nullable();
            $table->integer('phone')->unique();
            $table->string('address',250)->nullable();
            $table->string('image',100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',200);
            $table->integer('role_id');
            $table->longText('fcm_token');
            $table->rememberToken();
            $table->timestamps();

            $table->index('email','email');
            $table->index('phone','phone');
        });
    }
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
