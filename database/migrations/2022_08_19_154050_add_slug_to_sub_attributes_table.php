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
        Schema::table('sub_attributes', function (Blueprint $table) {
            $table->string('slug')->after('value');
        });

        $attributes = \App\Models\SubAttribute::query()->withTrashed()->get();
        foreach ($attributes as $attribute)
        {
            $name = $attribute->getTranslation('value', 'en');
            $attribute->slug =check_slug(\App\Models\SubAttribute::query(), convertToKebabCase( $name));
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
        Schema::table('sub_attributes', function (Blueprint $table) {
            $table->dropColumn('slug');

        });
    }
};
