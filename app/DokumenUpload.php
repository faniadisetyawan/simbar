<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DokumenUpload extends Model
{
    protected $table = 'dokumen_uploads';

    protected $fillable = [
        'slug_dokumen_tambah',
        'slug_dokumen_kurang',
        'file_upload',
        'created_by',
    ];

    public function get_created_by() 
    {
        return $this->belongsTo('App\User', 'created_by')->select(
            'id', 'nama'
        );
    }
}
