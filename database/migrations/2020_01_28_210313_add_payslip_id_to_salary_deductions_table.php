<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayslipIdToSalaryDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_deductions', function (Blueprint $table) {
            $table->bigInteger('pay_slip_id')->unsigned()->after('deduction_amount');
            $table->foreign('pay_slip_id')->references('id')->on('pay_slips');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_deductions', function (Blueprint $table) {
            $table->dropColumn('pay_slip_id');
        });
    }
}
