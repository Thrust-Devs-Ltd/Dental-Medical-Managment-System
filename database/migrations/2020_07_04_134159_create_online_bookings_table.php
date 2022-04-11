<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('full_name');
            $table->string('phone_no')->nullable();
            $table->string('email')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('start_time');
            $table->longText('message')->nullable();
            $table->enum('visit_history', ['Yes', 'No'])->default('No');
            $table->enum('status', ['Accepted', 'Rejected', 'Waiting'])->default('Waiting');

            $table->bigInteger('insurance_company_id')->unsigned()->nullable();
            $table->bigInteger('branch_id')->unsigned()->nullable();

            $table->foreign('insurance_company_id')->references('id')->on('insurance_companies');
            $table->foreign('branch_id')->references('id')->on('branches');

            $table->timestamp('sort_by');
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
        Schema::dropIfExists('online_bookings');
    }
}
