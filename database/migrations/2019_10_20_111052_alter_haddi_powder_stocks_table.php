<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterHaddiPowderStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('haddi_powder_stocks', function(Blueprint $table){
            $table->decimal('opening_haddi', 20, 2)->default('0');
            $table->decimal('opening_powder', 20, 2)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('haddi_powder_stocks', function(Blueprint $table){
            $table->dropColumn('opening_haddi');
            $table->dropColumn('opening_powder');
        });
    }
}
