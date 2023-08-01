<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumenUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumen_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('slug_dokumen_tambah')->nullable();
            $table->string('slug_dokumen_kurang')->nullable();
            $table->char('file_upload', 25);
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade');
            $table->timestamps();

            $table->index(['slug_dokumen_tambah', 'slug_dokumen_kurang']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dokumen_uploads');
    }
}
