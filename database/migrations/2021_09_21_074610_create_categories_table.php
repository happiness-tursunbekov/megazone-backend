<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('singular')->nullable();
            $table->unsignedBigInteger('icon_id')->nullable();
            $table->foreign('icon_id')->on('files')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->on('categories')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->on('users')
                ->references('id')->onDelete('restrict');
            $table->unsignedFloat('max_price')->nullable();
            $table->unsignedInteger('old_id')->nullable();
            $table->string('old_type')->nullable();
            $table->boolean('has_color')->default(false);
            $table->unsignedBigInteger('size_field_id')->nullable();
            $table->foreign('size_field_id')->on('fields')
                ->references('id')->onDelete('restrict');
            $table->boolean('has_model')->default(false);
            $table->boolean('has_series')->default(false);
            $table->smallInteger('order')->default(1);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('categories');
    }
}
