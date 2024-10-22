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
        Schema::create('variables', function (Blueprint $table) {
            $table->id();
            $table->integer('starting_number');
            $table->string('mall_id');
            $table->string('cash');
            $table->string('tng');
            $table->string('visa');
            $table->string('master_card');
            $table->string('amex');
            $table->string('vouchers');
            $table->string('others');
            $table->string('shop');
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
        Schema::dropIfExists('variables');
    }
};
