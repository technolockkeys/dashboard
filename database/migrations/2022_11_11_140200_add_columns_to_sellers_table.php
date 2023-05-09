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
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('whatsapp_number')->nullable()->after('email');
            $table->string('phone')->nullable()->after('email');
            $table->string('skype')->nullable()->after('email');
            $table->string('facebook')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn('whatsapp_number');
            $table->dropColumn('phone');
            $table->dropColumn('skype');
            $table->dropColumn('facebook');
        });
    }
};
