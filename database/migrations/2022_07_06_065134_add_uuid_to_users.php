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
            $table->string('uuid')->after('id') ;
        });
        $users = \App\Models\User::withTrashed()->get();

        foreach ($users as $user){
            $user->uuid = "TLKC-".date('y')."00".($user->id +500);
            $user->save();
        }
        Schema::table('users', function (Blueprint $table) {
            $table->string('uuid')->after('id')->unique()->change() ;
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
            //
        });
    }
};
