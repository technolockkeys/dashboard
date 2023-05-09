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
        Schema::table('products' , function (Blueprint $table) {
            $table->date('start_date_discount')->nullable()->after('discount_value');
            $table->date('end_date_discount')->nullable()->after('discount_value');
            $table->longText('faq')->nullable()->after('summary_name');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
