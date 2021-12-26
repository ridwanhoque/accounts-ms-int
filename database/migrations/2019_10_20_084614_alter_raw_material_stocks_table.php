<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRawMaterialStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_material_stocks', function(Blueprint $table){
            $table->integer('opening_bags')->default('0');
            $table->integer('available_bags')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_material_stocks', function (Blueprint $table) {
            $table->dropColumn('opening_bags');
            $table->dropColumn('available_bags');
        });
    }
}
