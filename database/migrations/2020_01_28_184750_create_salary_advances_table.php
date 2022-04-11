<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryAdvancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_advances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('payment_classification', ['Salary', 'Advance'])->nullable();
            $table->double('advance_amount');
            $table->string('advance_month');
            $table->date('payment_date');
            $table->enum('payment_method', ['Cash', 'Bank Transfer', 'Cheque', 'Mobile Money', 'Online Wallet'])->nullable();

            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();

            $table->foreign('employee_id')->references('id')->on('users');
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
        Schema::dropIfExists('salary_advances');
    }
}
