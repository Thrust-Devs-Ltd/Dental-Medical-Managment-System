<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelfAccountDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('self_account_deposits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount');
            $table->enum('payment_method', ['Cash', 'Mobile Money', 'Cheque'])->nullable();
            $table->date('payment_date')->nullable();

            $table->bigInteger('self_account_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();
            $table->foreign('self_account_id')->references('id')->on('self_accounts');
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
        Schema::dropIfExists('self_account_deposits');
    }
}
