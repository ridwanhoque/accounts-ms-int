<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePettycashExpenseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pettycash_expense_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedBigInteger('pettycash_expense_id');
            $table->foreign('pettycash_expense_id')->references('id')->on('pettycash_expenses');
            $table->unsignedBigInteger('pettycash_chart_id');
            $table->foreign('pettycash_chart_id')->references('id')->on('pettycash_charts');
            $table->text('purpose')->nullable();
            $table->decimal('amount', 20, 2)->default('0');
            $table->unique(['pettycash_expense_id', 'pettycash_chart_id'], 'exp_chart');
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
        Schema::dropIfExists('pettycash_expense_details');
    }
}
