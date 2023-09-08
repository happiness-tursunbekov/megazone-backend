<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('store_category_id');
            $table->foreign('store_category_id')->on('store_categories');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->on('products')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->on('stores')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->on('brands')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->foreign('model_id')->on('brand_models')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('series_id')->nullable();
            $table->foreign('series_id')->on('brand_series')
                ->references('id')->onDelete('restrict');
            $table->unsignedFloat('price');
            $table->unsignedBigInteger('currency_id');
            $table->foreign('currency_id')->on('currencies')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->on('countries')
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
        Schema::dropIfExists('store_products');
    }
}
