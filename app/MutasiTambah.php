<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MutasiTambah extends Model
{
    protected $table = 'mutasi_tambah';

    protected $fillable = [
        'id',
        'kode_pembukuan',
        'kode_perolehan',
        'kode_penggunaan',
        'tgl_pembukuan',
        'kode_jenis_dokumen',
        'no_dokumen',
        'slug_dokumen',
        'tgl_dokumen',
        'uraian_dokumen',
        'bidang_id',
        'barang_id',
        'jumlah_barang',
        'harga_satuan',
        'nilai_perolehan',
        'saldo_jumlah_barang',
        'saldo_harga_satuan',
        'saldo_nilai_perolehan',
        'tgl_expired',
        'keterangan',
        'opname_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'jumlah_barang' => 'integer',
        'harga_satuan' => 'float',
        'nilai_perolehan' => 'float',
        'saldo_jumlah_barang' => 'integer',
        'saldo_harga_satuan' => 'float',
        'saldo_nilai_perolehan' => 'float',
    ];

    public function bidang() 
    {
        return $this->belongsTo('App\Bidang', 'bidang_id')->withTrashed();
    }

    public function master_persediaan() 
    {
        return $this->belongsTo('App\PersediaanMaster', 'barang_id')->withTrashed();
    }

    public function get_created_by() 
    {
        return $this->belongsTo('App\User', 'created_by')->select(
            'id',
            'nama'
        );
    }
}
