<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ad_banners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_approved')->default(0);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('ad_banners');
    }
};
