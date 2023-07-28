<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefKapitalisasi extends Model
{
    protected $table = 'ref_kapitalisasi';

    protected $primaryKey = 'kode';

    protected $fillable = ['kode', 'nama'];
}
