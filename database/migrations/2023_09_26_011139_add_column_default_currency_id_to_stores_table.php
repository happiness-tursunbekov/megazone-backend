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
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('default_currency_id');
            $table->unsignedBigInteger('default_currency_type_id')->nullable();
            $table->foreign('default_currency_type_id')->on('currency_types')
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
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('default_currency_type_id');
            $table->unsignedBigInteger('default_currency_id')->nullable();
        });
    }
};
