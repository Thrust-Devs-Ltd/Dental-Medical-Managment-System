<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalCardItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_card_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('card_photo');
            $table->bigInteger('medical_card_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();

            $table->foreign('medical_card_id')->references('id')->on('medical_cards');
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
        Schema::dropIfExists('medical_card_items');
    }
}
