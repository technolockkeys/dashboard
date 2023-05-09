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
        Schema::table('orders',function (Blueprint $table){
            $table->longText('feedback')->after('phone')->nullable();
            $table->date('feedback_date')->after('phone')->nullable();
            $table->boolean('feedback_send_email')->after('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    Schema::dropColumns('orders',['date_feedback', 'feedback', 'feedback_send_email' ]);
    }
};
