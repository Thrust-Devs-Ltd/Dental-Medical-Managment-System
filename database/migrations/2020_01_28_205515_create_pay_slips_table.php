<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaySlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_slips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payslip_month');
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('employee_contract_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();

            $table->foreign('employee_id')->references('id')->on('users');
            $table->foreign('employee_contract_id')->references('id')->on('employee_contracts');
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
        Schema::dropIfExists('pay_slips');
    }
}
