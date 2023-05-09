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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            //user
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            //address
            $table->bigInteger('address_id')->unsigned();
            $table->foreign('address_id')->references('id')->on('addresses');
            //payment method ..
            $table->enum('payment_method', ['paypal', 'stripe']);
            $table->enum('payment_status', ['unpaid', 'paid', 'failed']);

            $table->double('total')->default(0);
            $table->double('shipping')->default(0);
            $table->enum('status', ['canceled', 'completed', 'failed', 'on_hold', 'pending_payment', 'processing', 'refunded']);

            //has coupon
            $table->boolean('has_coupon')->default(false);
            $table->double('coupon_value')->default(0);
            //order type
            $table->enum('type',['normal','offer'])->default('normal');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('orders_products', function (Blueprint $table) {
            $table->id();
            //Order
            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('orders');
            //Product
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            //Quantity
            $table->integer('quantity')->default(0);
            //Price
            $table->double('price')->default(0);
            $table->double('shipping_cost')->default(0);

            //Color
            $table->bigInteger('color_id')->unsigned()->nullable();
            $table->foreign('color_id')->references('id')->on('colors');
            //Attributes
            $table->json('attributes')->nullable();
            //package
            $table->boolean('has_package')->default(false);
            $table->double('package_price')->nullable();
            //price
            $table->double('original_price')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('orders_payments', function (Blueprint $table){
            $table->id();
            //orders
            $table->bigInteger('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');
            //user
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->double('amount')->default(0);
            $table->enum('payment_method' , ['stripe','paypal']);

            $table->text('payment_details')->nullable();

            $table->enum('status',['created','captured','denied','refund','pending','voided']);
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
        Schema::dropIfExists('orders');
        Schema::dropIfExists('orders_products');
        Schema::dropIfExists('orders_payments');
    }
};
