<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('icon_id')->nullable();
            $table->foreign('icon_id')->on('files')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->on('store_categories')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->on('stores')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('match_category_id');
            $table->foreign('match_category_id')->on('categories')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->on('users')
                ->references('id')->onDelete('restrict');
            $table->unsignedFloat('max_price')->nullable();
            $table->integer('order')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('has_color')->default(false);
            $table->unsignedBigInteger('size_field_id')->nullable();
            $table->foreign('size_field_id')->on('fields')
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
        Schema::dropIfExists('store_categories');
    }
}
