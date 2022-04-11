<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount');
            $table->bigInteger('quotation_id')->unsigned();
            $table->bigInteger('medical_service_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();

            $table->foreign('quotation_id')->references('id')->on('quotations');
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
        Schema::dropIfExists('quotation_items');
    }
}
