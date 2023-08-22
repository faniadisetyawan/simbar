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

    public function jenis_dokumen() 
    {
        return $this->belongsTo('App\RefJenisDokumen', 'kode_jenis_dokumen');
    }

    public function master_persediaan() 
    {
        return $this->belongsTo('App\PersediaanMaster', 'barang_id')->withTrashed();
    }

    public function mutasi_tambah() 
    {
        return $this->hasMany('App\MutasiTambah', 'opname_id');
    }

    public function mutasi_kurang() 
    {
        return $this->hasMany('App\MutasiKurang', 'opname_id');
    }
}
