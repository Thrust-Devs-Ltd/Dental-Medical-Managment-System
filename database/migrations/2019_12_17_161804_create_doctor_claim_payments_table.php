<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorClaimPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_claim_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount');
            $table->date('payment_date');

            $table->bigInteger('doctor_claim_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();

            $table->foreign('doctor_claim_id')->references('id')->on('doctor_claims');
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
        Schema::dropIfExists('doctor_claim_payments');
    }
}
