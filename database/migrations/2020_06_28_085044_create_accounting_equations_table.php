<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountingEquationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_equations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('sort_by');
            $table->enum('active_tab', ['yes', 'no'])->default('no');

            $table->bigInteger('_who_added')->unsigned()->nullable();
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
        Schema::dropIfExists('accounting_equations');
    }
}
