<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KodefikasiRincianObjek extends Model
{
    protected $table = 'kodefikasi_rincian_objek';

    protected $primaryKey = 'kode';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'uraian',
        'kode_objek',
        'nilai_kapitalisasi',
        'masa_manfaat',
    ];

    public function objek() 
    {
        return $this->belongsTo('App\KodefikasiObjek', 'kode_objek');
    }

    public function sub_rincian_objek() 
    {
        return $this->hasMany('App\KodefikasiSubRincianObjek', 'kode_rincian_objek');
    }
}
