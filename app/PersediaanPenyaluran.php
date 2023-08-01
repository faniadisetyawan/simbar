<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersediaanPenyaluran extends Model
{
    protected $table = 'persediaan_penyaluran';

    protected $fillable = [
        'kode_pembukuan',
        'tgl_pembukuan',
        'kode_jenis_dokumen',
        'no_dokumen',
        'slug_dokumen',
        'tgl_dokumen',
        'uraian_dokumen',
        'bidang_id',
        'barang_id',
        'jumlah_barang_permintaan',
        'jumlah_barang_sisa',
        'jumlah_barang_usulan',
        'keperluan',
        'keterangan',
        'created_by',
        'updated_by',
    ];
}
