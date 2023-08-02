<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MutasiKurang extends Model
{
    protected $table = 'mutasi_kurang';

    protected $fillable = [
        'kode_pembukuan',
        'kode_penggunaan',
        'tgl_pembukuan',
        'kode_jenis_dokumen',
        'no_dokumen',
        'slug_dokumen',
        'tgl_dokumen',
        'uraian_dokumen',
        'bidang_id',
        'mutasi_tambah_id',
        'barang_id',
        'jumlah_barang',
        'harga_satuan',
        'nilai_perolehan',
        'saldo_jumlah_barang',
        'saldo_harga_satuan',
        'saldo_nilai_perolehan',
        'keterangan',
        'dasar_penyaluran_id',
        'opname_id',
        'created_by',
        'updated_by',
    ];
}
