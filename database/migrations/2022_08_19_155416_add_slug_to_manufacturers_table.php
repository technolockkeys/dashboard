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
        Schema::table('manufacturers', function (Blueprint $table) {
            $table->string('slug')->after('title');
        });
        $manufacturers = \App\Models\Manufacturer::query()->withTrashed()->get();
        foreach ($manufacturers as $manufacturer)
        {
            $manufacturer->update([
               'slug' => check_slug(\App\Models\Manufacturer::query(), convertToKebabCase($manufacturer->getTranslation('title', 'en')))
//               'slug' => convertToKebabCase($manufacturer->getTranslation('title', 'en'))
            ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manufacturers', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
