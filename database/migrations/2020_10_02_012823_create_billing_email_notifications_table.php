<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingEmailNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_email_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('message')->nullable();
            $table->double('item_id')->nullable();
            $table->enum('notification_type', ['Invoice', 'Quotation', 'Self Account'])->nullable();
            $table->enum('status', ['sent', 'failed'])->default('sent');

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
        Schema::dropIfExists('billing_email_notifications');
    }
}
