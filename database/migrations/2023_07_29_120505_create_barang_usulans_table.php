<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangUsulansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_usulan', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_pembukuan');
            $table->foreignId('bidang_id')->constrained('bidang')->onUpdate('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onUpdate('cascade');
            $table->integer('kode_label');
            $table->unsignedInteger('jumlah_barang');
            $table->enum('kondisi', ['B','RR','RB']);
            $table->text('keterangan')->nullable();
            $table->char('foto', 25)->nullable();
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade');
            $table->foreignId('updated_by')->constrained('users')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onUpdate('cascade');
            $table->datetime('approved_at')->nullable();
            $table->timestamps();

            $table->index('tgl_pembukuan');
            $table->index(['approved_by', 'approved_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang_usulan');
    }
}
