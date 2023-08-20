<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersediaanOpname extends Model
{
    protected $table = 'persediaan_opname';

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
        'jumlah_barang',
        'keterangan',
        'created_by',
        'updated_by',
    ];
}
