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
        Schema::create('sellers_wallet', function (Blueprint $table) {
            $table->id();
            //seller
            $table->bigInteger('seller_id')->unsigned();
            $table->foreign('seller_id')->references('id')->on('sellers');
            //amount
            $table->double('amount')->default(0);
            $table->double('before_balance')->default(0);
            $table->double('balance')->default(0);
            //status
            $table->enum('type', ['refund', 'withdraw', 'commission']);
            $table->enum('status', ['approve', 'pending', 'waiting', 'cancelled']);
            //order
            $table->bigInteger('order_id')->nullable()->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');
            //note
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('sellers', function (Blueprint $table) {
            $table->double('balance')->after('seller_product_rate')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_waller');
    }
};
