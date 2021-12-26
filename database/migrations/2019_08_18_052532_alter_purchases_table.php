<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign('purchases_supplier_id_foreign');
            $table->dropColumn('supplier_id');
            $table->unsignedBigInteger('party_id');
            $table->foreign('party_id')->references('id')->on('parties');
            $table->decimal('total_paid', 20, 2)->default('0');
        });
    
    }

    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function(Blueprint $table){
            $table->dropForeign('purchases_party_id_foreign');
            $table->dropColumn('party_id');
            $table->dropColumn('total_paid');
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
        });
    }
}
