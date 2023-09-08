<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_size', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->on('products')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('size_id')->nullable();
            $table->foreign('size_id')->on('options')
                ->references('id')->onDelete('restrict');
            $table->string('value')->nullable();
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
        Schema::dropIfExists('product_size');
    }
}
