<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaftarMuatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_muats', function (Blueprint $table) {
            $table->increments('id_dm');
            $table->string('kode_daftar_muat');
            $table->Integer('nomor_sa');
            $table->string('supir');
            $table->string('no_mobil');
           
            $table->string('nama_customer');
            $table->string('nama_penerima');
            $table->integer('jumlah_barang');
            $table->double('berat_barang');
            $table->double('harga');
            $table->double('total_harga');
            $table->double('total_berat_barang')->nullable();
            $table->double('total_semua_harga')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('daftar_muats');
    }
}
