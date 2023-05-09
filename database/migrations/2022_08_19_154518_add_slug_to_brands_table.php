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
        Schema::table('brands', function (Blueprint $table) {
            $table->string('slug')->after('make');
        });

        $brands = \App\Models\Brand::query()->withTrashed()->get();

        foreach ($brands as $brand)
        {
            $name = $brand->make;
            $brand->slug = check_slug(\App\Models\Brand::query(), convertToKebabCase( $name));
            $brand->save();
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
            $table->dropColumn('slug');
        });
    }
};
