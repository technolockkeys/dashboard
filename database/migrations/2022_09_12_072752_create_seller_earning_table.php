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
        Schema::create('seller_earning', function (Blueprint $table) {
            $table->id();
            #region seller
            $table->bigInteger('seller_id')->unsigned();
            $table->foreign('seller_id')->references('id')->on('sellers');
            #endregion

            #region date
            $table->year('year');
            $table->integer('month');
            $table->date('date');

            $table->double('total_orders');
            $table->double('commissions');

            $table->double('earnings');
            #endregion



            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_earning');
    }
};
