<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameJournalDateToContraVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('contra_vouchers', 'date')) {
            Schema::table('contra_vouchers', function(Blueprint $table){
                $table->renameColumn('date', 'journal_date');
            });
        }
        Schema::table('contra_vouchers', function (Blueprint $table) {
            $table->renameColumn('journal_date', 'contra_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contra_vouchers', function (Blueprint $table) {
            $table->renameColumn('contra_date', 'journal_date');
        });
    }
}
