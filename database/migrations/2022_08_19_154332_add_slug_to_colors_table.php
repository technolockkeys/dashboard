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
        Schema::table('colors', function (Blueprint $table) {
            $table->string('slug')->after('name');
        });
        $colors = \App\Models\Color::query()->withTrashed()->get();


        foreach ($colors as $color)
        {
            $name = $color->name;
            $color->slug = check_slug(\App\Models\Color::query(), convertToKebabCase($name  )) ;
            $color->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
