<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount');
            $table->date('payment_date')->nullable();
            $table->enum('payment_method', ['Cash', 'Online Wallet', 'Insurance', 'Mobile Money', 'Cheque'])
                ->nullable();
            $table->string('cheque_no')->nullable()->comment('if the client make a payment with a cheque');
            $table->string('account_name')->nullable()->comment('cheque account holder');
            $table->string('bank_name')->nullable()->comment('name of the bank ');

            $table->bigInteger('invoice_id')->unsigned();
            $table->bigInteger('insurance_company_id')->unsigned()->nullable();
            $table->bigInteger('_who_added')->unsigned();

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('insurance_company_id')->references('id')->on('insurance_companies');
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
        Schema::dropIfExists('invoice_payments');
    }
}
