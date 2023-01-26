<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('terminal_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('allocate_place',50)->nullable();
            $table->string('reg_no',50);
            $table->string('imei',50);
            $table->boolean('status')->default(0);
            $table->timestamp('status_updated_at')->nullable();
            $table->boolean('is_approved')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->bigInteger('user_id')->nullable()->references('id')->on('users')->unsigned();
            $table->timestamps();

            $table->index('reg_no','reg_no');
            $table->index('imei','imei');
            $table->index('status','status');
            $table->index('is_approved','is_approved');
            $table->index('user_id','user_id');
        });
    }
    public function down()
    {
        Schema::dropIfExists('terminal_actions');
    }
};
