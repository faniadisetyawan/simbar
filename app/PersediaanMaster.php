<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersediaanMaster extends Model
{
    use SoftDeletes;
    
    protected $table = 'persediaan_master';

    protected $fillable = [
        'id',
        'kode_barang',
        'kode_register',
        'nama_barang',
        'spesifikasi',
        'satuan',
        'user_id',
    ];

    public function kodefikasi() 
    {
        return $this->belongsTo('App\KodefikasiSubSubRincianObjek', 'kode_barang');
    }

    public function user() 
    {
        return $this->belongsTo('App\User', 'user_id')->withTrashed();
    }
}
