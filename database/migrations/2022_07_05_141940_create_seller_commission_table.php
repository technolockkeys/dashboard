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
        Schema::create('seller_commissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_id' )->unsigned();
            $table->foreign('seller_id' )->references('id')->on('sellers');

            $table->double('from');
            $table->double('to');
            $table->double('commission');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::dropColumns('sellers',['commission']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_commission');
    }
};
