<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_type', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->on('stores')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('store_type_id');
            $table->foreign('store_type_id')->on('store_types')
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
        Schema::dropIfExists('store_type');
    }
}
