<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('about')->nullable();
            $table->tinyText('slogan')->nullable();
            $table->string('slug')->unique();
            $table->boolean('active')->default(false);
            $table->unsignedBigInteger('icon_id')->nullable();
            $table->foreign('icon_id')->on('files')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('cover_id')->nullable();
            $table->foreign('cover_id')->on('files')
                ->references('id')->onDelete('restrict');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->on('addresses')
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
        Schema::dropIfExists('stores');
    }
}
