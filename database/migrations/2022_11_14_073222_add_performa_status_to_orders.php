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
        \Illuminate\Support\Facades\DB::statement("alter table orders modify status enum('canceled', 'completed', 'failed', 'on_hold', 'pending_payment', 'processing', 'refunded', 'waiting', 'proforma') not null;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        \Illuminate\Support\Facades\DB::statement("alter table orders modify status enum('canceled', 'completed', 'failed', 'on_hold', 'pending_payment', 'processing', 'refunded', 'waiting') not null;");
    }
};
