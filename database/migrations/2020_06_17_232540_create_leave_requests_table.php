<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('start_date')->unique();
            $table->double('duration')->nullable();//no of days
            $table->enum('status', ['Pending Approval', 'Rejected', 'Approved'])->default('Pending Approval');
            $table->date('action_date')->nullable();// date of approval

            $table->bigInteger('leave_type_id')->unsigned();
            $table->bigInteger('_who_added')->unsigned();
            $table->bigInteger('_approved_by')->unsigned()->nullable();

            $table->foreign('leave_type_id')->references('id')->on('leave_types');
            $table->foreign('_who_added')->references('id')->on('users');
            $table->foreign('_approved_by')->references('id')->on('users'); //leave approved by
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
        Schema::dropIfExists('leave_requests');
    }
}
