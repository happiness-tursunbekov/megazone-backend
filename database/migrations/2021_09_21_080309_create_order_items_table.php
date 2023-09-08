<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_product_id');
            $table->foreign('store_product_id')->on('store_products')
                ->references('id')->onDelete('restrict');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('color_id')->nullable();
            $table->foreign('color_id')->on('options')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('size_id')->nullable();
            $table->foreign('size_id')->on('options')
                ->references('id')->onDelete('restrict');
            $table->unsignedFloat('price');
            $table->unsignedBigInteger('currency_id');
            $table->foreign('currency_id')->on('currencies')
                ->references('id')->onDelete('restrict');
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
        Schema::dropIfExists('order_items');
    }
}
