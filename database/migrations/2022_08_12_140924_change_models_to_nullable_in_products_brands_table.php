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
        Schema::table('products_brands', function (Blueprint $table) {
            $table->bigInteger('brand_model_id')->nullable()->unsigned()->change();

            $table->bigInteger('brand_model_year_id')->nullable()->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_brands', function (Blueprint $table) {
            $table->bigInteger('brand_model_id')->unsigned()->change();

            $table->bigInteger('brand_model_year_id')->unsigned()->change();

        });
    }
};
