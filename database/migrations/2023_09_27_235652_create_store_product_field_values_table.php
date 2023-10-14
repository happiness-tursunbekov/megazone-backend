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
        Schema::create('store_product_field_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_product_field_id');
            $table->foreign('store_product_field_id')->on('store_product_fields')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('option_id')->nullable();
            $table->foreign('option_id')->on('options')
                ->references('id')->onDelete('restrict');
            $table->string('value')->nullable();
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
        Schema::dropIfExists('store_product_field_values');
    }
};
