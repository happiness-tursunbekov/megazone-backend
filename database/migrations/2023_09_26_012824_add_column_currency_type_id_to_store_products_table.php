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
            $table->unsignedBigInteger('currency_type_id')->nullable();
            $table->foreign('currency_type_id')->on('currency_types')
                ->references('id')->onDelete('restrict');
            $table->dropColumn('currency_id');
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
            $table->dropColumn('currency_type_id');
            $table->unsignedBigInteger('currency_id')->nullable();
        });
    }
};
