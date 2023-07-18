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
}
