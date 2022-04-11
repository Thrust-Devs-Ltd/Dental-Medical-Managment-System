<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpenseCategoryIdToExpenseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expense_items', function (Blueprint $table) {
            $table->bigInteger('expense_category_id')->unsigned()->nullable()->after('name');
            $table->foreign('expense_category_id')->references('id')->on('expense_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_items', function (Blueprint $table) {
            $table->dropColumn('expense_category_id');
        });
    }
}
