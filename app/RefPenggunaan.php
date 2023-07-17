<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefPenggunaan extends Model
{
    protected $table = 'ref_penggunaan';

    protected $fillable = ['kode', 'nama'];
}
