<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchIdToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('appointment_no');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('start_time')->nullable()->after('end_date');

            $table->bigInteger('branch_id')->unsigned()->nullable()->after('patient_id');
            $table->foreign('branch_id')->references('id')->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('start_time');

            $table->dropColumn('branch_id');

        });
    }
}
