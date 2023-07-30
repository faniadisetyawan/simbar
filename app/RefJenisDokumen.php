<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefJenisDokumen extends Model
{
    protected $table = 'ref_jenis_dokumen';

    protected $primaryKey = 'kode';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['kode', 'nama'];
}
