<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('terminals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reg_no',50);
            $table->unsignedBigInteger('terminal_id')->references('id')->on('terminal_actions');
            $table->unsignedBigInteger('user_id')->references('id')->on('users');
            $table->boolean('shutter_sensor_status');
            $table->timestamp('shutter_sensor_updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('smoke_sensor_status');
            $table->timestamp('smoke_sensor_updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('gas_sensor_status');
            $table->timestamp('gas_sensor_updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('motion_sensor_status');
            $table->timestamp('motion_sensor_updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('is_terminal_on');
            $table->timestamp('terminal_updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            $table->index('terminal_id','terminal_id');
            $table->index('user_id','user_id');
            $table->index('is_terminal_on','is_terminal_on');
        });
    }
    public function down()
    {
        Schema::dropIfExists('terminals');
    }
};
