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
        $dhl ='dhl';
        $aramex ='aramex';
        $fedex ='fedex';
        $ups ='ups';

        Schema::table('orders', function (Blueprint $table) use (
            $dhl,
            $aramex,
            $fedex,
            $ups

        ) {


            $table->enum('shipping_method', [$dhl, $ups,$aramex,$fedex])->nullable()->after('address_id');
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
            $table->dropColumn('shipping_method');
        });
    }
};
