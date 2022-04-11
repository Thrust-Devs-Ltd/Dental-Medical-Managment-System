<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChartOfAccountIdToExpenseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expense_categories', function (Blueprint $table) {
            $table->bigInteger('chart_of_account_item_id')->unsigned()->nullable()->after('name');

            $table->foreign('chart_of_account_item_id')->references('id')->on('chart_of_account_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_categories', function (Blueprint $table) {
            $table->dropColumn('chart_of_account_item_id');
        });
    }
}
