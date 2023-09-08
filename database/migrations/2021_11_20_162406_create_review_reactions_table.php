<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_reactions', function (Blueprint $table) {
            $table->id();
            $table->boolean('helpful');
            $table->string('ip',15);
            $table->unsignedBigInteger('review_id');
            $table->foreign('review_id')->on('reviews')
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
        Schema::dropIfExists('review_reactions');
    }
}
