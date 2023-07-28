<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KodefikasiSubSubRincianObjek extends Model
{
    protected $table = 'kodefikasi_sub_sub_rincian_objek';

    protected $primaryKey = 'kode';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'uraian',
        'kode_sub_rincian_objek',
        'nilai_kapitalisasi',
        'masa_manfaat',
    ];

    public function sub_rincian_objek() 
    {
        return $this->belongsTo('App\KodefikasiSubRincianObjek', 'kode_sub_rincian_objek');
    }

    public function persediaan_master() 
    {
        return $this->hasMany('App\PersediaanMaster', 'kode_barang');
    }
}
