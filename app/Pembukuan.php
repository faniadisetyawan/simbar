<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembukuan extends Model
{
    protected $table = 'pembukuan';

    protected $fillable = ['kode', 'nama'];
}
