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
        Schema::create('download_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('download_id')->references('id')->on('downloads');
            $table->string('name');
            $table->string('link');
            $table->enum('type', ['software','maker', 'driver', 'extra', 'user_manual','configuration']);
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
        Schema::dropIfExists('download_attributes');
    }
};
