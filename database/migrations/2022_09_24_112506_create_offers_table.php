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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->double('from');
            $table->double('to');
            $table->integer('days');
            $table->double('discount');
            $table->enum('discount_type', ['Percentage','Amount']);
            $table->enum('type', ['Order', 'Product']);
            $table->double('minimum_shopping')->nullable();
            $table->json('products_ids')->nullable();
            $table->boolean('free_shipping')->default(0);
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('offers');
    }
};
