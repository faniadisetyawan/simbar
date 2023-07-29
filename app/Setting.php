<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'setting';

    protected $primaryKey = null;

    public $incrementing = false;
    
    public $timestamps = false;

    protected $fillable = [
        'app_name',
        'app_description',
        'app_company',
        'app_version',
        'tahun_anggaran',
    ];
}
