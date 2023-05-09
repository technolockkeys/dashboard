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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('parent_id')->nullable();
            $table->enum('type',['physical','software'])->default('physical');
            $table->string('banner')->nullable()->comment(' Banner 200*200');
            $table->string('icon')->nullable()->comment(' Icon 32*32');
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
};
