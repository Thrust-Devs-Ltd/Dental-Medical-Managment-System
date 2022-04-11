<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('payment_date');
            $table->double('amount');
            $table->enum('payment_method', ['Cash', 'Mobile Money', 'Cheque', 'Online Wallet'])->nullable();
            $table->bigInteger('expense_id')->unsigned()->nullable();
            $table->bigInteger('_who_added')->unsigned();

            $table->foreign('expense_id')->references('id')->on('expenses');
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
        Schema::dropIfExists('expense_payments');
    }
}
