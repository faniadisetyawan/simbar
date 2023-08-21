<?php

namespace App\Traits;

use DB;
use App\Bidang;
use App\PersediaanMaster;
use App\MutasiTambah;
use App\MutasiKurang;
use App\Setting;

trait ProviderTraits
{
    protected $setting;
    protected $startDate;

    public function __construct() 
    {
        $this->setting = Setting::first();
        $this->startDate = $this->setting->tahun_anggaran . '-01-01';
    }

    protected function _setting() 
    {
        return Setting::first();
    }

    protected function _startDate() 
    {
        return $this->_setting()->tahun_anggaran . '-01-01';
    }

    public function _generateNUSP($kodeBarang) 
    {
        $maxValue = PersediaanMaster::where('kode_barang', $kodeBarang)->max('kode_register');

        return sprintf("%06s", (int)$maxValue + 1);
    }

    public function _pageTitleFromSlug($slug) 
    {
        $pageTitle = '';

        switch ($slug) {
            case 'persediaan': $pageTitle = 'Persediaan'; break;
            case 'tanah': $pageTitle = 'Tanah'; break;
            case 'peralatan-mesin': $pageTitle = 'Peralatan dan Mesin'; break;
            case 'gedung-bangunan': $pageTitle = 'Gedung dan Bangunan'; break;
            case 'jij': $pageTitle = 'Jalan, Irigasi dan Jaringan'; break;
            case 'atl': $pageTitle = 'Aset Tetap Lainnya'; break;
            case 'kdp': $pageTitle = 'Konstruksi Dalam Pengerjaan'; break;
            case 'atb': $pageTitle = 'Aset Tidak Berwujud'; break;
            case 'aset-lain': $pageTitle = 'Aset Lain-Lain'; break;
            case 'pengadaan': $pageTitle = 'Pengadaan'; break;
            case 'hibah': $pageTitle = 'Hibah'; break;
            default: $pageTitle = ''; break;
        }

        return $pageTitle;
    }

    public function _getBidang() 
    {
        return Bidang::get();
    }

    public function _canUpdatedMutasiTambah($id) 
    {
        $data = MutasiKurang::where('mutasi_tambah_id', $id)->first();

        if ($data === NULL) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // private function _getMutasiTambahByDate($tglPembukuan) 
    // {
    //     $query = MutasiTambah::query();
    //     $query->select(
    //         'barang_id',
    //         DB::raw('SUM(jumlah_barang) AS jumlah_barang'),
    //         DB::raw('SUM(nilai_perolehan) AS nilai_perolehan')
    //     );
    //     $query->whereBetween('tgl_pembukuan', [$this->startDate, $tglPembukuan]);
    //     $query->groupBy('barang_id');

    //     return $query->get();
    // }
    
    // public function _availableStock($search, $tglPembukuan) 
    // {
    //     $mutasiTambah = $this->_getMutasiTambahByDate($tglPembukuan);

    //     $query = PersediaanMaster::query();
    //     $query->with(['kodefikasi']);
    //     $query->whereIn('id', $mutasiTambah->pluck('barang_id'));
    //     $query->where(function ($q) use ($search) {
    //         $q->orWhere('nama_barang', 'like', '%'.$search.'%');
    //         $q->orWhere('spesifikasi', 'like', '%'.$search.'%');
    //     });
    //     $query->orderBy('kode_barang');
        
    //     $data = [];
    //     foreach ($query->get() as $barang) {
    //         foreach ($mutasiTambah as $mutasi) {
    //             if ($mutasi->barang_id == $barang->id) {
    //                 $barang->jumlah_barang = $mutasi->jumlah_barang;
    //             }
    //         }

    //         array_push($data, $barang);
    //     }

    //     return $data;
    // }

    // public function _findAvailableStock($id, $tglPembukuan) 
    // {
    //     $mutasiTambah = $this->_getMutasiTambahByDate($tglPembukuan);

    //     $data = PersediaanMaster::with(['kodefikasi'])->findOrFail($id);
    //     $data->jumlah_barang = 0;
    //     $data->nilai_perolehan = 0;
    //     $data->saldo_jumlah_barang = 0;
    //     $data->saldo_nilai_perolehan = 0;
        
    //     foreach ($mutasiTambah as $mutasi) {
    //         if ($mutasi->barang_id == $data->id) {
    //             $data->jumlah_barang = $mutasi->jumlah_barang;
    //             $data->harga_satuan = $mutasi->harga_satuan;
    //             $data->nilai_perolehan = $mutasi->nilai_perolehan;
    //             $data->saldo_jumlah_barang = $mutasi->saldo_jumlah_barang;
    //             $data->saldo_nilai_perolehan = $mutasi->saldo_nilai_perolehan;
    //         }
    //     }

    //     return $data;
    // }
}
