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
             $table->text('device_token')->nullable()->after('status');
        });
        Schema::table('sellers', function (Blueprint $table) {
             $table->text('device_token')->nullable()->after('status');
        });
        Schema::table('admins', function (Blueprint $table) {
             $table->text('device_token')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('users' ,'device_token' );
        Schema::dropColumns('sellers' ,'device_token' );
        Schema::dropColumns('admins' ,'device_token' );
    }
};
