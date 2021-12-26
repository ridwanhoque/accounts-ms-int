<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_delivery_details', function(Blueprint $table){
            $table->dropForeign('product_delivery_details_daily_production_details_id_foreign');
            $table->dropColumn('daily_production_details_id');
            $table->decimal('pack', 20, 2)->default('0');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_delivery_details', function(Blueprint $table){
            $table->unsignedBigInteger('daily_production_details_id');
            $table->foreign('daily_production_details_id')->references('id')->on('daily_production_details');
            $table->dropColumn('pack');
        });
    }
}
