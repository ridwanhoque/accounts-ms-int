<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToSheetWastageStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sheet_wastage_stocks', function (Blueprint $table) {
            $table->dropForeign('sheet_wastage_stocks_raw_material_id_foreign');
            $table->dropColumn('raw_material_id');
            $table->unsignedBigInteger('sub_raw_material_id');
            $table->foreign('sub_raw_material_id', 'sws_srmid')->references('id')->on('sub_raw_materials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sheet_wastage_stocks', function (Blueprint $table) {
            $table->dropForeign('sws_srmid');
            $table->dropColumn('sub_raw_material_id');
        });
    }
}
