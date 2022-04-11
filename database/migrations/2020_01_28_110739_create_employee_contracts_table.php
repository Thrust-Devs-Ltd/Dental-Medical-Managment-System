<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('start_date');
            $table->integer('years');
            $table->double('basic_salary');
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();
            $table->enum('status', ['Active', 'Expired'])->default('Active');


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
        Schema::dropIfExists('employee_contracts');
    }
}
