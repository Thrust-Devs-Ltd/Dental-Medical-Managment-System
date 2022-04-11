<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('appointment_no')->unique()->nullable();
            $table->longText('notes')->nullable();
            $table->enum('status', ['Waiting', 'Treatment Complete', 'Treatment Incomplete', 'Rejected'])->default('Waiting');
            $table->enum('visit_information', ['Single Treatment', 'Review Treatment'])->nullable();

            $table->bigInteger('doctor_id')->unsigned()->nullable();
            $table->bigInteger('patient_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();

            $table->foreign('doctor_id')->references('id')->on('users');
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
        Schema::dropIfExists('appointments');
    }
}
