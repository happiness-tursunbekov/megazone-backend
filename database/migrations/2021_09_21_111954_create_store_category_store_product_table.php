<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreCategoryStoreProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_category_store_product', function (Blueprint $table) {
            $table->unsignedBigInteger('store_category_id');
            $table->foreign('store_category_id')->on('store_categories')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('store_product_id');
            $table->foreign('store_product_id')->on('store_products')
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
        Schema::dropIfExists('store_category_store_product');
    }
}
