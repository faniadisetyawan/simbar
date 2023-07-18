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
}
