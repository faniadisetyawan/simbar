<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KodefikasiSubRincianObjek extends Model
{
    protected $table = 'kodefikasi_sub_rincian_objek';

    protected $primaryKey = 'kode';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'uraian',
        'kode_rincian_objek',
        'nilai_kapitalisasi',
        'masa_manfaat',
    ];

    public function rincian_objek() 
    {
        return $this->belongsTo('App\KodefikasiRincianObjek', 'kode_rincian_objek');
    }

    public function sub_sub_rincian_objek() 
    {
        return $this->hasMany('App\KodefikasiSubSubRincianObjek', 'kode_sub_rincian_objek');
    }
}
