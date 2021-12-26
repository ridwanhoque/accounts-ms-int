<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBatchIdToDirectProductionSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('direct_production_sheets', function (Blueprint $table) {
            $table->unsignedBigInteger('batch_id')->nullable()->after('direct_production_id');
            $table->foreign('batch_id', 'dps_bid')->references('id')->on('batches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('direct_production_sheets', function (Blueprint $table) {
            $table->dropForeign('dps_bid');
            $table->dropColumn('batch_id');
        });
    }
}
