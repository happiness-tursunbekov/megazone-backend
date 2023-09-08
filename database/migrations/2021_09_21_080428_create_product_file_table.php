<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_file', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->on('products')
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
        Schema::dropIfExists('product_image');
    }
}
