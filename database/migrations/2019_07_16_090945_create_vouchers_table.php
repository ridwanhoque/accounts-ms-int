<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('approved_by');
            $table->date('voucher_date');
            $table->enum('voucher_type',['debit','credit']);
            $table->string('voucher_reference')->unique()->nullable();
            $table->decimal('payable_amount',14,2);
            $table->decimal('paid_amount',14,2)->default('0');
            $table->decimal('due_amount',14,2);
            $table->string('cheque_number')->unique()->nullable();
            $table->dateTime('approved_at');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('party_id')->references('id')->on('parties');
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
