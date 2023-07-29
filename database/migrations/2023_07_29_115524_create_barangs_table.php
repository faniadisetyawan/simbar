<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->char('kode_barang', 18);
            $table->char('kode_register', 6);
            $table->char('kode_neraca', 18);
            $table->string('nama_barang');
            $table->string('spesifikasi')->nullable();
            $table->char('tahun_pengadaan', 4);
            $table->unsignedInteger('jumlah_barang');
            $table->string('satuan', 25);
            $table->decimal('harga_satuan', 19, 2);
            $table->decimal('nilai_perolehan', 19, 2);
            $table->text('keterangan')->nullable();
            $table->char('kode_kapitalisasi', 2);
            $table->char('no_sertifikat', 25)->nullable();
            $table->date('tgl_sertifikat')->nullable();
            $table->decimal('luas_tanah', 8, 2)->nullable();
            $table->char('no_polisi', 15)->nullable();
            $table->char('no_rangka', 50)->nullable();
            $table->char('no_mesin', 50)->nullable();
            $table->unsignedInteger('jumlah_lantai')->nullable();
            $table->decimal('luas_bangunan', 8, 2)->nullable();
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade');
            $table->foreignId('updated_by')->constrained('users')->onUpdate('cascade');
            $table->timestamps();

            $table->foreign('kode_barang')->references('kode')->on('kodefikasi_sub_sub_rincian_objek')->onUpdate('cascade');
            $table->foreign('kode_neraca')->references('kode')->on('kodefikasi_sub_sub_rincian_objek')->onUpdate('cascade');
            $table->index('kode_register');
            $table->index('nama_barang');
            $table->index('spesifikasi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang');
    }
}
