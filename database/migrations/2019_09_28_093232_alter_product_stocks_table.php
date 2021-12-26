<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function(Blueprint $table){
            $table->decimal('available_pack', 20, 2)->default('0');
            $table->decimal('produced_pack', 20, 2)->default('0');
            $table->decimal('sold_pack', 20, 2)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_stocks', function(Blueprint $table){
            $table->dropColumn('available_pack');
            $table->dropColumn('produced_pack');
            $table->dropColumn('sold_pack');
        });
    }
}
