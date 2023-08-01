<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersediaanPenyaluransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persediaan_penyaluran', function (Blueprint $table) {
            $table->id();
            $table->char('kode_pembukuan', 2);
            $table->date('tgl_pembukuan');
            $table->char('kode_jenis_dokumen', 2);
            $table->string('no_dokumen', 100);
            $table->string('slug_dokumen');
            $table->date('tgl_dokumen');
            $table->text('uraian_dokumen')->nullable();
            $table->foreignId('bidang_id')->constrained('bidang')->onUpdate('cascade');
            $table->foreignId('barang_id')->constrained('persediaan_master')->onUpdate('cascade');
            $table->unsignedInteger('jumlah_barang_permintaan')->default(0);
            $table->unsignedInteger('jumlah_barang_sisa')->default(0);
            $table->unsignedInteger('jumlah_barang_usulan')->default(0);
            $table->text('keperluan')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade');
            $table->foreignId('updated_by')->constrained('users')->onUpdate('cascade');
            $table->timestamps();

            $table->foreign('kode_pembukuan')->references('kode')->on('pembukuan')->onUpdate('cascade');
            $table->foreign('kode_jenis_dokumen')->references('kode')->on('ref_jenis_dokumen')->onUpdate('cascade');
            $table->index('tgl_pembukuan');
            $table->index('no_dokumen');
            $table->index('slug_dokumen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persediaan_penyaluran');
    }
}
