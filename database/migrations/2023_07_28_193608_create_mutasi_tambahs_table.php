<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutasiTambahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutasi_tambah', function (Blueprint $table) {
            $table->id();
            $table->char('kode_pembukuan', 2);
            $table->char('kode_perolehan', 2);
            $table->char('kode_penggunaan', 2)->nullable();
            $table->date('tgl_pembukuan');
            $table->char('kode_jenis_dokumen', 2)->nullable();
            $table->string('no_dokumen', 100)->nullable();
            $table->string('slug_dokumen')->nullable();
            $table->date('tgl_dokumen')->nullable();
            $table->text('uraian_dokumen')->nullable();
            $table->foreignId('bidang_id')->nullable()->constrained('bidang')->onUpdate('cascade');
            $table->foreignId('barang_id')->constrained('persediaan_master')->onUpdate('cascade');
            $table->unsignedInteger('jumlah_barang');
            $table->decimal('harga_satuan', 19, 2)->default(0);
            $table->decimal('nilai_perolehan', 19, 2)->default(0);
            $table->unsignedInteger('saldo_jumlah_barang')->default(0);
            $table->decimal('saldo_harga_satuan', 19, 2)->default(0);
            $table->decimal('saldo_nilai_perolehan', 19, 2)->default(0);
            $table->date('tgl_expired')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('opname_id')->nullable()->constrained('persediaan_opname')->onUpdate('cascade');
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade');
            $table->foreignId('updated_by')->constrained('users')->onUpdate('cascade');
            $table->timestamps();

            $table->foreign('kode_pembukuan')->references('kode')->on('pembukuan')->onUpdate('cascade');
            $table->foreign('kode_perolehan')->references('kode')->on('ref_perolehan')->onUpdate('cascade');
            $table->foreign('kode_penggunaan')->references('kode')->on('ref_penggunaan')->onUpdate('cascade');
            $table->foreign('kode_jenis_dokumen')->references('kode')->on('ref_jenis_dokumen')->onUpdate('cascade');
            $table->index(['tgl_pembukuan', 'no_dokumen', 'slug_dokumen']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutasi_tambah');
    }
}
