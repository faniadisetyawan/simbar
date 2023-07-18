<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersediaanMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persediaan_master', function (Blueprint $table) {
            $table->id();
            $table->char('kode_barang', 18);
            $table->char('kode_register', 6);
            $table->string('nama_barang');
            $table->string('spesifikasi');
            $table->string('satuan');
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('kode_barang')->references('kode')->on('kodefikasi_sub_sub_rincian_objek')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persediaan_master');
    }
}
