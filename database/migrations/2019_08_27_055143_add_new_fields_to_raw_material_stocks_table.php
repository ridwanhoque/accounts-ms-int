<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToRawMaterialStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_material_stocks', function (Blueprint $table) {
            $table->dropForeign('raw_material_stocks_raw_material_id_foreign');
            $table->dropColumn('raw_material_id');
            $table->unsignedBigInteger('sub_raw_material_id');
            $table->foreign('sub_raw_material_id', 'rms_sr_id')->references('id')->on('sub_raw_materials');
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
            $table->dropForeign('rms_sr_id');
            $table->dropColumn('sub_raw_material_id');
        });
    }
}
