<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalAccountChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_account_charts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedBigInteger('journal_voucher_id');
            $table->foreign('journal_voucher_id')->references('id')->on('journal_vouchers');
            $table->unsignedBigInteger('chart_of_account_id');
            $table->foreign('chart_of_account_id')->references('id')->on('chart_of_accounts');
            $table->string('description')->nullable();
            $table->decimal('payable_amount')->default('0');
            $table->decimal('paid_amount')->default('0');
            $table->tinyInteger('status')->default('0');
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
        Schema::dropIfExists('journal_account_charts');
    }
}
