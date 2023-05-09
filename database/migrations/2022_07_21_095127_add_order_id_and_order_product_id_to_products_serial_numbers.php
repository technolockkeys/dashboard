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
        Schema::table('products_serial_numbers', function (Blueprint $table) {
            //order id
            $table->bigInteger('order_id')->after('product_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('orders');
            //order product ..
            $table->bigInteger('order_product_id')->after('product_id')->unsigned()->nullable();
            $table->foreign('order_product_id')->references('id')->on('orders_products');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_serial_numbers', function (Blueprint $table) {
            //
        });
    }
};
