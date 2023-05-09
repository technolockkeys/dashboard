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
        Schema::table('users', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $table->dropForeign('users_seller_id_foreign');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $table->foreign('seller_id')->on('sellers')->references('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $table->dropForeign('users_seller_id_foreign');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $table->foreign('seller_id')->on('sellers')->references('id');

        });
    }
};
