<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDebitCreditToChartOfAccountBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chart_of_account_balances', function (Blueprint $table) {
            $table->decimal('debit_amount', 20, 2)->default('0')->after('opening_balance');
			$table->decimal('credit_amount', 20, 2)->default('0')->after('debit_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chart_of_account_balances', function (Blueprint $table) {
            $table->dropColumn('debit_amount');
			$table->dropColumn('credit_amount');
        });
    }
}
