<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProductFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_product_field', function (Blueprint $table) {
            $table->unsignedBigInteger('store_product_id');
            $table->foreign('store_product_id')->on('store_products')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('field_id');
            $table->foreign('field_id')->on('fields')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('option_id')->nullable();
            $table->foreign('option_id')->on('options')
                ->references('id')->onDelete('restrict');
            $table->string('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_product_field');
    }
}
