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
        Schema::table('store_products', function (Blueprint $table) {
            $table->unsignedBigInteger('color_id')->nullable();
            $table->foreign('color_id')->on('options')
                ->references('id')->onDelete('restrict');
        });

        Schema::table('store_product_size', function (Blueprint $table) {
            $table->dropColumn('color_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_products', function (Blueprint $table) {
            $table->dropColumn('color_id');
        });

        Schema::table('store_product_size', function (Blueprint $table) {
            $table->unsignedBigInteger('color_id')->nullable();
        });
    }
};