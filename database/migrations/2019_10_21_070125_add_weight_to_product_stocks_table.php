<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeightToProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->decimal('opening_weight', 20, 2)->default('0');
            $table->decimal('produced_weight', 20, 2)->default('0');
            $table->decimal('sold_weight', 20, 2)->default('0');
            $table->decimal('available_weight', 20, 2)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropColumn(['opening_weight', 'produced_weight', 'sold_weight', 'available_weight']);
        });
    }
}
