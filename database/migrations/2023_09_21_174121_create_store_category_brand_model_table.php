<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_category_brand_model', function (Blueprint $table) {
            $table->unsignedBigInteger('store_category_id');
            $table->foreign('store_category_id')->on('store_categories')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('brand_model_id');
            $table->foreign('brand_model_id')->on('brand_models')
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
        Schema::dropIfExists('store_category_brand_model');
    }
};
