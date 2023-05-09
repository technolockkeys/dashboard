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
        Schema::table('reviews', function (Blueprint $table) {
            $table->integer('order')->after('rating')->index()->nullable();
        });
        Schema::create('reviews_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->nullable()->references('id')->on('reviews');
            $table->morphs('user');
            $table->text('comment')->nullable();
            $table->json('files')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('reviews_replies');
        Schema::dropColumns('reviews' ,'order');
    }
};
