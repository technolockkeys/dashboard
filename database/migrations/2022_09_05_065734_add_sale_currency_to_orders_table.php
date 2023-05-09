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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->after('address_id')->references('id')->on('currencies');
            $table->double('total_in_sale_currency')->nullable()->after('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_currency_id_foreign');
            $table->dropIndex('orders_currency_id_index');
            $table->dropColumn('currency_id');
            $table->dropColumn('total_in_sale_currency');

        });
    }
};
