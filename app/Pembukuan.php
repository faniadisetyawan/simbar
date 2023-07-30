<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembukuan extends Model
{
    protected $table = 'pembukuan';

    protected $primaryKey = 'kode';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['kode', 'nama'];
}
