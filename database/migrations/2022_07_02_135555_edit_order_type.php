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
        \App\Models\OrderPayment::query()->delete();
        \App\Models\OrdersProducts::query()->delete();
        \App\Models\Order::query()->delete();
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('type', ['order', 'proforma'])->default('order')->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
