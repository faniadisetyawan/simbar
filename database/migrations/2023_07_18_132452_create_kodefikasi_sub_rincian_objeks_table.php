<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKodefikasiSubRincianObjeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kodefikasi_sub_rincian_objek', function (Blueprint $table) {
            $table->char('kode', 14);
            $table->string('uraian');
            $table->char('kode_rincian_objek', 11);
            $table->bigInteger('nilai_kapitalisasi')->default(0);
            $table->integer('masa_manfaat')->default(0);

            $table->primary('kode');
            $table->foreign('kode_rincian_objek')->references('kode')->on('kodefikasi_rincian_objek')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kodefikasi_sub_rincian_objek');
    }
}
