<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreCategoryFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_category_field', function (Blueprint $table) {
            $table->unsignedBigInteger('store_category_id');
            $table->foreign('store_category_id')->on('store_categories')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('field_id');
            $table->foreign('field_id')->on('fields')
                ->references('id')->onDelete('restrict');
            $table->boolean('required')->default(false);
            $table->boolean('filter')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_category_field');
    }
}
