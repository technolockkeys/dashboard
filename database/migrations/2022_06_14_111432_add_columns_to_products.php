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
        if (Schema::hasColumn('products', 'name')) {
            Schema::dropColumns('products', 'name');
        }
        if (Schema::hasColumn('products', 'price')) {
            Schema::dropColumns('products', 'price');
        }
        if (Schema::hasColumn('products', 'status')) {
            Schema::dropColumns('products', 'status');
        }
        if (Schema::hasColumn('products', 'deleted_at')) {
            Schema::dropColumns('products', 'deleted_at');
        }
        if (Schema::hasColumn('products', 'created_at')) {
            Schema::dropColumns('products', 'created_at');
        }
        if (Schema::hasColumn('products', 'updated_at')) {
            Schema::dropColumns('products', 'updated_at');
        }

        Schema::table('products', function (Blueprint $table) {
            #region info
            $table->text('title');
            $table->enum('type',['software' , 'physical'])->default('physical');
            $table->text('summary_name');
            $table->string('slug')->unique();
            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('min_purchase_qty')->default(1);
            $table->longText('description')->nullable();
            $table->integer('priority')->default(1);
            $table->json('accessories')->nullable();
            #endregion

            #region images and media
            $table->integer('image');
            $table->integer('twitter_image')->nullable();
            $table->json('gallery')->nullable();
            #endregion

            #region video + pdf
            $table->json('videos')->nullable();
            $table->string('pdf')->nullable();
            #endregion


            #region stock + price
            $table->double('price')->default(0);
            $table->double('sale_price')->default(0);
            $table->enum('discount_type', ['none', 'fixed', 'percent']);
            $table->decimal('discount_value')->default(0.0);
            $table->string('sku');
            $table->integer('quantity')->default(0);
            $table->boolean('stock_visibility')->default(1);
            #endregion

            #region meta data
            $table->string('meta_title');
            $table->text('meta_description');
            $table->integer('meta_image');
            #endregion


            #region switch
            $table->boolean('is_best_seller')->default(0);
            $table->boolean('is_super_sales')->default(0);
            $table->boolean('is_visibility')->default(0);
            $table->boolean('is_saudi_branch')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_today_deal')->default(0);
            $table->boolean('is_free_shipping')->default(0);
            $table->boolean('hide_price')->comment('hide price and show message contact us fpr take price')->default(0);
            $table->boolean('status')->default(1);
            #endregion

            #region bundle
            $table->boolean('is_bundle')->default(0);
            $table->json('bundled')->nullable();
            #endregion

            $table->timestamps();
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
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
