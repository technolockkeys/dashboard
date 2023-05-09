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
            $table->bigInteger('country_id')->unsigned()->nullable()->after('coupon_value');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->bigInteger('city_id')->unsigned()->nullable()->after('country_id');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->string('address')->nullable()->after('city_id');
            $table->string('postal_code')->nullable()->after('address');
            $table->string('phone')->nullable()->after('postal_code');
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
            //
        });
    }
};
