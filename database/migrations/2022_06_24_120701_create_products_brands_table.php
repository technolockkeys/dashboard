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
        Schema::create('products_brands', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->bigInteger('brand_model_id')->unsigned();
            $table->foreign('brand_model_id')->references('id')->on('brand_models');

            $table->bigInteger('brand_model_year_id')->unsigned();
            $table->foreign('brand_model_year_id')->references('id')->on('brand_model_years');

            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');

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
        Schema::dropIfExists('products_brandse');
    }
};
