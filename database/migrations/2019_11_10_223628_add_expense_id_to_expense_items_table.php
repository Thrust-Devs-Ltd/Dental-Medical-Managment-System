<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpenseIdToExpenseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expense_items', function (Blueprint $table) {
            $table->bigInteger('expense_id')->unsigned()->nullable()->after('price');;
            $table->foreign('expense_id')->references('id')->on('expenses');

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
            $table->dropColumn('expense_id');
        });
    }
}
