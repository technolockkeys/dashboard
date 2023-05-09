<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            DB::select("TRUNCATE `brand_model_years`");
            DB::select("TRUNCATE `brand_models`");
            DB::select("TRUNCATE `brands`");
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        $columns = [
            'model',
            'year',
            'object_id',
            'category'

        ];
        foreach ($columns as $item) {
            if (Schema::hasColumn('brands', $item)) {
                Schema::table('brands', function (Blueprint $table) use ($item) {
                    $table->dropColumn($item);
                });
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->text('object_id')->nullable();
            $table->text('year')->nullable();
            $table->text('model')->nullable();
            $table->text('category')->nullable();

        });
    }
};
