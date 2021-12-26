<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContraVoucherDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contra_voucher_details', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedBigInteger('contra_voucher_id');
            $table->foreign('contra_voucher_id')->references('id')->on('contra_vouchers');
            $table->unsignedBigInteger('chart_of_account_id');
            $table->foreign('chart_of_account_id')->references('id')->on('chart_of_accounts');
            $table->text('description')->nullable();
            $table->decimal('amount', 20, 2)->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contra_voucher_details');
    }
}
