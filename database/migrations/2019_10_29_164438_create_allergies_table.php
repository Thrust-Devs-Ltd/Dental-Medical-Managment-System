<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllergiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allergies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('drug');
            $table->string('body_reaction')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');

            $table->bigInteger('patient_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();

            $table->foreign('patient_id')->references('id')->on('patients');
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
        Schema::dropIfExists('allergies');
    }
}
