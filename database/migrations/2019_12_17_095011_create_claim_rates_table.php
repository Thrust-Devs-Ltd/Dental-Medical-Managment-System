<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('cash_rate');
            $table->double('insurance_rate');
            $table->enum('status', ['active', 'deactivated'])->default('active');
            $table->bigInteger('doctor_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();

            $table->foreign('doctor_id')->references('id')->on('users');
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
        Schema::dropIfExists('claim_rates');
    }
}
