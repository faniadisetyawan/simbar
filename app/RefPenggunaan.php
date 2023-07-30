<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefPenggunaan extends Model
{
    protected $table = 'ref_penggunaan';

    protected $primaryKey = 'kode';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['kode', 'nama'];
}
