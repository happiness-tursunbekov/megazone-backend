<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('new');
            $table->text('note')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedFloat('amount');
            $table->unsignedBigInteger('currency_id');
            $table->foreign('currency_id')->on('currencies')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')->on('addresses')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->on('users')
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
        Schema::dropIfExists('orders');
    }
}
