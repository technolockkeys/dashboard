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
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign('addresses_city_id_foreign');
        });
        Schema::dropColumns('addresses' , 'city_id');
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('city')->after('country_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->renameColumn('city', 'city_id');

        });
        Schema::table('addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id')->change();
            $table->foreign('city_id')->references('id')->on('cities');

        });
    }
};
