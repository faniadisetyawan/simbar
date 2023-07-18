<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKodefikasiJenisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kodefikasi_jenis', function (Blueprint $table) {
            $table->char('kode', 5);
            $table->string('uraian');
            $table->char('kode_kelompok', 3);

            $table->primary('kode');
            $table->foreign('kode_kelompok')->references('kode')->on('kodefikasi_kelompok')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kodefikasi_jenis');
    }
}
