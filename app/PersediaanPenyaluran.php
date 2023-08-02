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
        'parent_id',
        'created_by',
        'updated_by',
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
