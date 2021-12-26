<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToAccountInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_information', function (Blueprint $table) {
            $table->tinyInteger('default_account')->default('1');
            $table->decimal('account_balance', 20, 2)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_information', function (Blueprint $table) {
            $table->dropColumn('default_account');
            $table->dropColumn('account_balance');
        });
    }
}
