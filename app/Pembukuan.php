<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembukuan extends Model
{
    protected $table = 'pembukuan';

    protected $primaryKey = 'kode';

    protected $fillable = ['kode', 'nama'];
}
