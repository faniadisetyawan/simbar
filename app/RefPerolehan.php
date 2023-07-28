<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefPerolehan extends Model
{
    protected $table = 'ref_perolehan';

    protected $primaryKey = 'kode';

    protected $fillable = ['kode', 'nama'];
}
