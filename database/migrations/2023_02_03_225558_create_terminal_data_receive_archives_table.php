<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminal_data_receive_archives', function (Blueprint $table) {
            $table->id();
            $table->string('terminal_data',300);
            $table->string('reg_no',50);
            $table->smallInteger('shutter_sensor_status');
            $table->smallInteger('smoke_sensor_status');
            $table->smallInteger('gas_sensor_status');
            $table->smallInteger('motion_sensor_status');
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
        Schema::dropIfExists('terminal_data_receive_archives');
    }
};
