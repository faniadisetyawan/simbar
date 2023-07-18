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
}
