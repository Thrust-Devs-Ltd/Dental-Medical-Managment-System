<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount');
            $table->bigInteger('invoice_id')->unsigned();
            $table->bigInteger('medical_service_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('medical_service_id')->references('id')->on('medical_services');
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
        Schema::dropIfExists('invoice_items');
    }
}
