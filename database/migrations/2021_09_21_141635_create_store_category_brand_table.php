<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreCategoryBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_category_brand', function (Blueprint $table) {
            $table->unsignedBigInteger('store_category_id');
            $table->foreign('store_category_id')->on('store_categories')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->on('brands')
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
        Schema::dropIfExists('store_category_brand');
    }
}
