<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChartOfAccountItemIdToExpensePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expense_payments', function (Blueprint $table) {
            $table->bigInteger('payment_account_id')->unsigned()->nullable()->after('payment_method');

            $table->foreign('payment_account_id')->references('id')->on('chart_of_account_items');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_payments', function (Blueprint $table) {
            $table->dropColumn('payment_account_id');
        });
    }
}
