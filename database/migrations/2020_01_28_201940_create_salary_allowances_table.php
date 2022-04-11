<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_allowances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('allowance', ['House Rent Allowance', 'Medical Allowance', 'Bonus', 'Dearness Allowance', 'Travelling Allowance']);
            $table->double('allowance_amount');
            $table->bigInteger('_who_added')->unsigned();
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
        Schema::dropIfExists('salary_allowances');
    }
}
