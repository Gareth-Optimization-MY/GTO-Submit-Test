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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('mall_id');
            $table->string('location');
            $table->string('shop');
            $table->string('fields')->nullable();
            $table->string('cash')->nullable();
            $table->string('tng')->nullable();
            $table->string('visa')->nullable();
            $table->string('master_card')->nullable();
            $table->string('amex')->nullable();
            $table->string('vouchers')->nullable();
            $table->string('others')->nullable();
            $table->string('ftp_details')->nullable();
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
        Schema::dropIfExists('locations');
    }
};
