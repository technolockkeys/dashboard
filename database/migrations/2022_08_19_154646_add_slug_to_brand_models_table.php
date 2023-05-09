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
        Schema::table('brand_models', function (Blueprint $table) {
            $table->string('slug')->after('model');
        });
        $models = \App\Models\BrandModel::query()->withTrashed()->get();

        foreach ($models as $model)
        {
             $model->slug = check_slug(\App\Models\BrandModel::query(), convertToKebabCase( $model->model));
            $model->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brand_models', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
