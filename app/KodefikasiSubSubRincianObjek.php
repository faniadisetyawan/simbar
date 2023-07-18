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
}
