<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('terminal_data_receives', function (Blueprint $table) {
            $table->id();
            $table->string('terminal_data',300);
            $table->string('reg_no',50);
            $table->boolean('shutter_sensor_status');
            $table->boolean('smoke_sensor_status');
            $table->boolean('gas_sensor_status');
            $table->boolean('motion_sensor_status');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('terminal_data_receives');
    }
};
