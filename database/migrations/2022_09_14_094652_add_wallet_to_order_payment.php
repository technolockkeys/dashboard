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
        DB::statement("alter table orders_payments modify payment_method enum('stripe', 'paypal', 'stripe_link', 'transfer', 'wallet') not null;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        DB::statement("alter table orders_payments modify payment_method enum('stripe', 'paypal', 'stripe_link', 'transfer') not null;");

    }
};
