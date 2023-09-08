<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProductFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_product_file', function (Blueprint $table) {
            $table->unsignedBigInteger('store_product_id');
            $table->foreign('store_product_id')->on('store_products')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('file_id');
            $table->foreign('file_id')->on('files')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('color_id')->nullable();
            $table->foreign('color_id')->on('options')
                ->references('id')->onDelete('restrict');
            $table->boolean('in_stock')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_product_image');
    }
}
