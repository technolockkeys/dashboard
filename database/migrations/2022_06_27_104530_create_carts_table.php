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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            //user
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            //address
            $table->bigInteger('address_id')->unsigned()->nullable();
            $table->foreign('address_id')->references('id')->on('addresses');
            //product
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            //quantity
            $table->integer('quantity')->default(1);
            //price
            $table->double('price')->default(0);
            $table->double('shipping_cost')->default(0);
            //coupon
            $table->string('coupon_code')->nullable();
            $table->boolean('coupon_applied')->default(0);
            $table->double('discount')->default(0);
            //attributes + color
            //[color , attributes]
            $table->json('attributes')->default(json_encode([]))->nullable();

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
        Schema::dropIfExists('carts');
    }
};
