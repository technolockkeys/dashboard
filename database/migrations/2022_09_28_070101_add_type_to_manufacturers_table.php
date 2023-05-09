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
        Schema::table('manufacturers', function (Blueprint $table) {
            $table->boolean('software')->default(0);
            $table->boolean('token')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('manufacturers', function (Blueprint $table) {
            $table->dropColumn('software');
        });
        Schema::table('manufacturers', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
};
