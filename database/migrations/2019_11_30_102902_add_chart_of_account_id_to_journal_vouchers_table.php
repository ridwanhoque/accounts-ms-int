<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChartOfAccountIdToJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('journal_vouchers', function (Blueprint $table) {
            $table->date('date')->nullable();
            $table->unsignedBigInteger('chart_of_account_id')->nullable();
            $table->foreign('chart_of_account_id', 'jv_coaid')->references('id')->on('chart_of_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropForeign('jv_coaid');
            $table->dropColumn('chart_of_account_id');
        });
    }
}
