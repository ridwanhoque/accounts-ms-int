<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameReceiveDateFromPaymentDateToReceiveVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receive_vouchers', function (Blueprint $table) {
            $table->renameColumn('payment_date', 'receive_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receive_vouchers', function (Blueprint $table) {
            $table->renameColumn('receive_date', 'payment_date');
        });
    }
}
