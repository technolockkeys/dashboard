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
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable()->references('id')->on('countries');
            $table->foreignId('city_id')->nullable()->references('id')->on('cities');
            $table->string('name');
            $table->enum('provider_type', ['email', 'google','facebook','phone']);
            $table->string('email')->unique()->nullable();
            $table->string('google_id')->unique()->nullable();
            $table->string('facebook_id')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('verification_code')->nullable();
            $table->string('new_verification_code')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('avatar')->nullable();
            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('street')->nullable();
            $table->string('company_name')->nullable();
            $table->string('website_url')->nullable();
            $table->string('type_of_business')->nullable();
            $table->string('postal_code')->nullable();
            $table->double('balance')->default(0.00);
            $table->string('referral_code')->default(1);
            $table->boolean('status')->default(1);
            $table->dateTime('email_verified_at')->nullable();
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
        Schema::dropIfExists('users');
    }
};
