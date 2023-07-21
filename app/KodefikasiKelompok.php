<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KodefikasiKelompok extends Model
{
    protected $table = 'kodefikasi_kelompok';

    protected $primaryKey = 'kode';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'uraian'
    ];

    public function jenis() 
    {
        return $this->hasMany('App\KodefikasiJenis', 'kode_kelompok');
    }
}
