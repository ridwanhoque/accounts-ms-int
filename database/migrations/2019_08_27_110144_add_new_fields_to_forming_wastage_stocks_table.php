<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToFormingWastageStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forming_wastage_stocks', function (Blueprint $table) {
            $table->dropForeign('forming_wastage_stocks_raw_material_id_foreign');
            $table->dropColumn('raw_material_id');
            $table->unsignedBigInteger('sheet_production_details_id')->nullable();
            $table->foreign('sheet_production_details_id', 'fws_spdid')->references('id')->on('sheet_production_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forming_wastage_stocks', function (Blueprint $table) {
            $table->dropForeign('fws_spdid');
            $table->dropColumn('sheet_production_details_id');
            $table->unsignedBigInteger('raw_material_id');
            $table->foreign('raw_material_id')->references('id')->on('raw_materials');
        });
    }
}