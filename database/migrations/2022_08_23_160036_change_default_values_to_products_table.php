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
        Schema::table('products', function (Blueprint $table) {
            $table->json('accessories')->nullable()->default("[]")->change();
            $table->json('videos')->nullable()->default("[]")->change();
            $table->json('bundled')->nullable()->default("[]")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('accessories')->nullable()->change();//
            $table->json('videos')->nullable()->change();//
            $table->json('bundled')->nullable()->change();//
        });
    }
};
