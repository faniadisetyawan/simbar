<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KodefikasiJenis extends Model
{
    protected $table = 'kodefikasi_jenis';

    protected $primaryKey = 'kode';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'uraian',
        'kode_kelompok',
    ];

    public function kelompok() 
    {
        return $this->belongsTo('App\KodefikasiKelompok', 'kode_kelompok');
    }

    public function objek() 
    {
        return $this->hasMany('App\KodefikasiObjek', 'kode_jenis');
    }
}
