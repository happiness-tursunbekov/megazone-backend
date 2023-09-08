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
        Schema::table('store_category_field', function (Blueprint $table) {
            $table->unsignedBigInteger('store_category_field_group_id')->nullable();
            $table->foreign('store_category_field_group_id')->references('id')->on('store_category_field_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_category_field', function (Blueprint $table) {
            $table->dropColumn('store_category_field_group_id');
        });
    }
};
