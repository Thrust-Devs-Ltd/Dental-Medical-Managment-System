<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDentalChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dental_charts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('treatment', ['Fracture', 'Restoration', 'Extraction'])->nullable();
            $table->double('tooth')->nullable();
            $table->double('section')->nullable();
            $table->string('color')->nullable();

            $table->bigInteger('appointment_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();

            $table->foreign('appointment_id')->references('id')->on('appointments');
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
        Schema::dropIfExists('dental_charts');
    }
}
