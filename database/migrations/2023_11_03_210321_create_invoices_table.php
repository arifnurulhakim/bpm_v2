<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kwitansi')->unique();
            $table->string('nomor_invoice')->unique();
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('sa_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['party_id']);
            $table->dropForeign(['sa_id']);
        });

        Schema::dropIfExists('invoices');
    }
}
