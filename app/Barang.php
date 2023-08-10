<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'id',
        'kode_barang',
        'kode_register',
        'kode_neraca',
        'nama_barang',
        'spesifikasi',
        'tahun_pengadaan',
        'jumlah_barang',
        'satuan',
        'harga_satuan',
        'nilai_perolehan',
        'keterangan',
        'kode_kapitalisasi',
        'no_sertifikat',
        'tgl_sertifikat',
        'luas_tanah',
        'no_polisi',
        'no_rangka',
        'no_mesin',
        'jumlah_lantai',
        'luas_bangunan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'jumlah_barang' => 'integer',
        'harga_satuan' => 'float',
        'nilai_perolehan' => 'float',
        'luas_tanah' => 'float',
        'jumlah_lantai' => 'integer',
        'luas_bangunan' => 'float',
    ];

    protected $appends = [
        'kapitalisasi',
    ];

    public function kodefikasi() 
    {
        return $this->belongsTo('App\KodefikasiSubSubRincianObjek', 'kode_barang');
    }

    public function getKapitalisasiAttribute() 
    {
        $text = "";

        if ($this->kode_kapitalisasi === '01') {
            $text = "Intrakomptabel";
        } else {
            $text = "Ekstrakomptabel";
        }
        
        return $text;
    }
}
