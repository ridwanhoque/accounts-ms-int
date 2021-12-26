<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToPurchaseReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_receive_details', function (Blueprint $table) {
            $table->dropForeign('purchase_receive_details_raw_material_id_foreign');
            $table->dropColumn('raw_material_id');
            $table->unsignedBigInteger('sub_raw_material_id');
            $table->foreign('sub_raw_material_id', 'prd_sr_id')->references('id')->on('sub_raw_materials');
            $table->decimal('quantity_bag', 20, 2)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_receive_details', function (Blueprint $table) {
            $table->dropForeign('prd_sr_id');
            $table->dropColumn('sub_raw_material_id');
            $table->dropColumn('quantity_bag');
        });
    }
}
