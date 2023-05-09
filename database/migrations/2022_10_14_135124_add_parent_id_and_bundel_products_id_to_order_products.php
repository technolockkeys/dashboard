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
            $table->bigInteger('parent_id')->comment('has parent id if product is bundle')->unsigned()->nullable();
             $table->json('bundles_products_id')->comment('has parent id if product is bundle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders_products', function (Blueprint $table) {
            $table->removeColumn('parent_id');
            $table->removeColumn('bundles_products_id');
        });
    }
};
