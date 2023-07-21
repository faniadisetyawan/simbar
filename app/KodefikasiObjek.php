<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KodefikasiObjek extends Model
{
    protected $table = 'kodefikasi_objek';

    protected $primaryKey = 'kode';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'uraian',
        'kode_jenis',
    ];

    public function jenis() 
    {
        return $this->belongsTo('App\KodefikasiJenis', 'kode_jenis');
    }

    public function rincian_objek() 
    {
        return $this->hasMany('App\KodefikasiRincianObjek', 'kode_objek');
    }
}
