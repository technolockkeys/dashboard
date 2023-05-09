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
        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('max_use')->after('status')->nullable()->default(1);
            $table->integer('per_user')->after('status')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {

            $columns = [
                'max_use',
                'per_user',
                'object_id',
                'category'

            ];
            foreach ($columns as $item) {
                if (Schema::hasColumn('coupons', $item)) {
                    Schema::table('coupons', function (Blueprint $table) use ($item) {
                        $table->dropColumn($item);
                    });
                }
            }
        });
    }
};
