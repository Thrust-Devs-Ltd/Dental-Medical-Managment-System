<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSelfAccountIdToInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->bigInteger('self_account_id')->unsigned()->nullable()->after('insurance_company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropColumn('self_account_id');
        });
    }
}
