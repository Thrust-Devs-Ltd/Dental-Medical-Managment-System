<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('patient_no')->nullable();
            $table->string('surname');
            $table->string('othername');
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('age')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('alternative_no')->nullable();
            $table->string('address')->nullable();
            $table->string('nin')->nullable();
            $table->string('photo')->nullable();
            $table->string('profession')->nullable();
            $table->string('next_of_kin')->nullable();
            $table->string('next_of_kin_no')->nullable();
            $table->text('next_of_kin_address')->nullable();
            $table->enum('has_insurance', ['Yes', 'No'])->default('No');
            $table->bigInteger('insurance_company_id')->unsigned()->nullable();
            $table->bigInteger('_who_added')->unsigned();

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
        Schema::dropIfExists('patients');
    }
}
