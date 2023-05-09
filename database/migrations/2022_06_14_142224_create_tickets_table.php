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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('system_id');
            $table->enum('type',['order', 'product','support','shipping', 'other']);
            $table->enum('status', ['pending','solved', 'open']);
            $table->text('subject');
            $table->text('details');
            $table->json('files')->nullable();
            $table->integer('viewed')->default(0);
            $table->integer('client_viewed');
            $table->dateTime('last_reply');
            $table->dateTime('sent_at');
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
        Schema::dropIfExists('tickets');
    }
};
