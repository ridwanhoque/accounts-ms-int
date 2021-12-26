<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToSheetproductiondetailsStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sheetproductiondetails_stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('sheet_production_details_id')->nullable()->change();
            $table->unsignedBigInteger('sheet_size_color_id')->nullable();
            $table->foreign('sheet_size_color_id', 'spds_ssc')->references('id')->on('sheet_size_colors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sheetproductiondetails_stocks', function (Blueprint $table) {
            $table->dropForeign('spds_ssc');
            $table->dropColumn('sheet_size_color_id');
        });
    }
}
