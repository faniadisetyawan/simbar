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

    public function pembukuan() 
    {
        return $this->belongsTo('App\Pembukuan', 'kode_pembukuan');
    }

    public function jenis_dokumen() 
    {
        return $this->belongsTo('App\RefJenisDokumen', 'kode_jenis_dokumen');
    }

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
