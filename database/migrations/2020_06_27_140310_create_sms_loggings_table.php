<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsLoggingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_loggings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phone_number');
            $table->longText('message');
            $table->double('cost');
            $table->enum('status', ['success', 'failed']);
            $table->bigInteger('patient_id')->unsigned()->nullable();
            $table->bigInteger('_who_added')->unsigned()->nullable();

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('_who_added')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_loggings');
    }
}
