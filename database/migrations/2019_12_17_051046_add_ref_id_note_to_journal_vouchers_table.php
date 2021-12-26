<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefIdNoteToJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('journal_vouchers', function (Blueprint $table) {
            $table->string('ref_id')->nullable();
            $table->text('note')->nullable();
            $table->string('journalable_type')->nullable()->change();
            $table->unsignedBigInteger('journalable_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journal_vouchers', function (Blueprint $table) {
            $table->dropColumn('ref_id');
            $table->dropColumn('note');
        });
    }
}
