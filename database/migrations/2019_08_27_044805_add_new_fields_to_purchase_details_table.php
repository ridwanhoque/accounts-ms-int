<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropForeign('purchase_details_raw_material_id_foreign');
            $table->dropColumn('raw_material_id');
            $table->unsignedBigInteger('sub_raw_material_id');
            $table->foreign('sub_raw_material_id', 'sr_id')->references('id')->on('sub_raw_materials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropForeign('sr_id');
            $table->dropColumn('sub_raw_material_id');
            $table->unsignedBigInteger('raw_material_id');
            $table->foreign('raw_material_id')->references('id')->on('raw_materials');
        });
    }
}
