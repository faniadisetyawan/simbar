<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefKapitalisasi extends Model
{
    protected $table = 'ref_kapitalisasi';

    protected $fillable = ['kode', 'nama'];
}
