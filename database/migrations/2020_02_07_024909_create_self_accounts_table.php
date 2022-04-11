<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelfAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('self_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account_no')->unique();
            $table->string('account_holder');
            $table->string('holder_phone_no')->nullable();
            $table->string('holder_email')->nullable();
            $table->string('holder_address')->nullable();

            $table->enum('is_active', ['true', 'false'])->default('true');

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
        Schema::dropIfExists('self_accounts');
    }
}
