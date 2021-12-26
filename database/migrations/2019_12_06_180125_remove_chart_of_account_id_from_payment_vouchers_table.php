<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveChartOfAccountIdFromPaymentVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            $table->dropForeign('pv_coa');
            $table->dropColumn('chart_of_account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            $table->unsignedBigInteger('chart_of_account_id')->nullable()->after('updated_by');
            $table->foreign('chart_of_account_id', 'pv_coa')->references('id')->on('chart_of_accounts');
        });
    }
}
