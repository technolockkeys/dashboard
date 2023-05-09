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
        Schema::table('orders', function (Blueprint $table) {
            $table->double('seller_commission')->nullable()->after('seller_id');
            $table->double('seller_manager_commission')->nullable()->after('seller_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {

            $columns = [
                'seller_commission',
                'seller_manager_commission',

            ];
            foreach ($columns as $item) {
                if (Schema::hasColumn('orders', $item)) {
                    Schema::table('orders', function (Blueprint $table) use ($item) {
                        $table->dropColumn($item);
                    });
                }
            }
        });
    }
};
