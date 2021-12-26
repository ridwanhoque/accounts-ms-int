<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $product_indexes = $sm->listTableIndexes('products');
            if (!array_key_exists('p_rmnc', $product_indexes)) {
                $table->unique(['raw_material_id', 'name', 'color_id'], 'p_rmnc');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            
        });
    }
}
