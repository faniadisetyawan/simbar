<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use App\MutasiTambah;
use App\MutasiKurang;

class PersediaanMaster extends Model
{
    use SoftDeletes;
    
    protected $table = 'persediaan_master';

    protected $fillable = [
        'id',
        'kode_barang',
        'kode_register',
        'nama_barang',
        'spesifikasi',
        'satuan',
        'user_id',
    ];

    protected $appends = [
        'stok',
    ];

    public function kodefikasi() 
    {
        return $this->belongsTo('App\KodefikasiSubSubRincianObjek', 'kode_barang')
            ->select(
                'kode',
                'uraian',
                'kode_sub_rincian_objek',
                DB::raw('CONCAT(kode, " ", uraian) AS concat')
            );
    }

    public function user() 
    {
        return $this->belongsTo('App\User', 'user_id')->withTrashed();
    }

    public function getStokAttribute() 
    {
        $mt = MutasiTambah::where('barang_id', $this->id)->sum('jumlah_barang');
        $mk = MutasiKurang::where('barang_id', $this->id)->sum('jumlah_barang');

        return (int)($mt - $mk);
    }
}
