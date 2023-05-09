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
        Schema::table('attributes', function (Blueprint $table) {
            $table->string('slug')->after('name');
        });
        $attributes = \App\Models\Attribute::query()->withTrashed()->get();

        foreach ($attributes as $attribute)
        {
            $name = $attribute->getTranslation('name', 'en');
            $attribute->slug = check_slug(\App\Models\Attribute::query(), convertToKebabCase( $name));
            $attribute->save();
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
