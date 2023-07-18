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
}
