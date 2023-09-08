<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_option', function (Blueprint $table) {
            $table->unsignedBigInteger('field_id');
            $table->foreign('field_id')->on('fields')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('option_id');
            $table->foreign('option_id')->on('options')
                ->references('id')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('field_option');
    }
}
