<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->on('categories')
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
        Schema::dropIfExists('products');
    }
}
