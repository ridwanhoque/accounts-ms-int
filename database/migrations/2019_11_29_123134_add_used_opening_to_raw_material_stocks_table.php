<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsedOpeningToRawMaterialStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_material_stocks', function (Blueprint $table) {
            $table->decimal('used_opening_quantity', 20, 2)->default('0');
            $table->decimal('available_opening_quantity', 20, 2)->default('0');
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
            $table->dropColumn('used_opening_quantity');
            $table->dropColumn('available_opening_quantity');
        });
    }
}
