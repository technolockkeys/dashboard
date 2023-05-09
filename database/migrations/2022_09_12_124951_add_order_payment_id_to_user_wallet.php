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
        Schema::table('user_wallet', function (Blueprint $table) {
            $table->bigInteger('order_payment_id')->after('order_id')->unsigned()->nullable();
            $table->foreign('order_payment_id')->references('id')->on('orders_payments');
            $table->morphs('create_by');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_wallet', function (Blueprint $table) {
            //
        });
    }
};
