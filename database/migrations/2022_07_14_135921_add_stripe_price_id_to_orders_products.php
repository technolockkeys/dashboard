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
        Schema::table('orders_products', function (Blueprint $table) {
            $table->string('stripe_price_id')->nullable();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->text('stripe_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('orders_products', 'stripe_price_id');
        Schema::dropColumns('orders', 'stripe_url');
    }
};
