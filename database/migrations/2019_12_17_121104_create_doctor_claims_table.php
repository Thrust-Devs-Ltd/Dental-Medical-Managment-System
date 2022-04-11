<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_claims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('claim_amount');
            $table->double('insurance_amount')->default(0);
            $table->double('cash_amount')->default(0);
            $table->enum('status', ['Pending', 'Approved'])->default('Pending');

            $table->bigInteger('claim_rate_id')->unsigned();
            $table->bigInteger('appointment_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();
            $table->bigInteger('approved_by')->unsigned()->nullable();

            $table->foreign('claim_rate_id')->references('id')->on('claim_rates');
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->foreign('_who_added')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
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
        Schema::dropIfExists('doctor_claims');
    }
}
