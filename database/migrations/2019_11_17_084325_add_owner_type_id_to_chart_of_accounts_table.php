<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOwnerTypeIdToChartOfAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->string('type')->nullable()->change();
            $table->string('head_name')->unique()->change();
            $table->string('account_code')->unique()->nullable()->after('head_name');
            $table->unsignedBigInteger('owner_type_id')->nullable();
            $table->foreign('owner_type_id', 'coa_otid')->references('id')->on('owner_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->dropColumn('account_code');
            $table->dropForeign('coa_otid');
            $table->dropColumn('owner_type_id');
        });
    }
}
