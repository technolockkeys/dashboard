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
        Schema::table('orders_payments', function (Blueprint $table) {
            $table->json('files')->after('status')->nullable();
            $table->text('stripe_url')->after('status')->nullable();
        });
        DB::statement("ALTER TABLE `orders_payments` CHANGE `payment_method` `payment_method` ENUM('stripe','paypal','stripe_link','transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
        Schema::dropColumns('orders','stripe_url');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_method', function (Blueprint $table) {
            //
        });
    }
};
