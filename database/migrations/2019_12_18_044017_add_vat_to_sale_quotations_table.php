<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVatToSaleQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_quotations', function (Blueprint $table) {
            $table->decimal('invoice_vat', 20, 2)->default('0')->after('invoice_discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_quotations', function (Blueprint $table) {
            $table->dropColumn('invoice_vat');
        });
    }
}
