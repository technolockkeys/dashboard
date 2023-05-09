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
        Schema::table('downloads', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->string('meta_title')->nullable()->change();
            $table->string('meta_description')->nullable()->change();
            $table->string('image')->nullable()->change();
            $table->string('internal_image')->nullable()->change();
            $table->string('screen_shot')->nullable()->change();
            $table->json('gallery')->nullable()->change();
            $table->json('video')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('downloads', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
            $table->string('meta_title')->nullable(false)->change();
            $table->string('meta_description')->nullable(false)->change();
            $table->string('image')->nullable(false)->change();
            $table->string('internal_image')->nullable(false)->change();
            $table->string('screen_shot')->nullable(false)->change();
            $table->json('gallery')->nullable(false)->change();
            $table->dropColumn('video');
//            $table->softDeletes();
        });
    }
};
