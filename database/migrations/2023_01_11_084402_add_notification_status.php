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
        DB::statement("alter table notifications modify type enum('out_of_stock', 'low_quantity', 'review_added', 'new_order', 'order_delivered', 'order_canceled', 'order_refunded' ,'order_is_paid' ,'stock_increase') not null;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("alter table notifications modify type enum('out_of_stock', 'low_quantity', 'review_added', 'new_order', 'order_delivered', 'order_canceled', 'order_refunded' ,'order_is_paid') not null;");

    }
};
