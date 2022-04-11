<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->double('amount');
            $table->enum('type', ['topup', 'sms', 'airtime', 'mobile money']);

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
        Schema::dropIfExists('sms_transactions');
    }
}
