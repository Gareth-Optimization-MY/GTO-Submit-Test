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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('template_use');
            $table->string('location');
            $table->text('input_fields');
            $table->text('ftp_details');
            $table->string('report_type');
            $table->string('report_date');
            $table->string('is_queued');
            $table->string('report_to_date');
            $table->string('schedule_cron');
            $table->string('shop');
            $table->string('filename');
            $table->string('mall_id');
            $table->dateTime('last_run');
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
        Schema::dropIfExists('reports');
    }
};
