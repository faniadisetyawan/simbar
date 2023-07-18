<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKodefikasiObjeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kodefikasi_objek', function (Blueprint $table) {
            $table->char('kode', 8);
            $table->string('uraian');
            $table->char('kode_jenis', 5);

            $table->primary('kode');
            $table->foreign('kode_jenis')->references('kode')->on('kodefikasi_jenis')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kodefikasi_objek');
    }
}
