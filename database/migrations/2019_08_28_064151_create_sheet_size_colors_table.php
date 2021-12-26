<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSheetSizeColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sheet_size_colors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sheet_size_id');
            $table->foreign('sheet_size_id')->references('id')->on('sheet_sizes')->onDelete('cascade');
            $table->unsignedBigInteger('color_id');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->unsignedBigInteger('sheet_production_details_id');
            $table->foreign('sheet_production_details_id')->references('id')->on('sheet_production_details')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sheet_size_colors');
    }
}
