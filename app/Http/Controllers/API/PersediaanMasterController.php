<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;
use App\MutasiTambah;
use App\MutasiKurang;
use App\PersediaanMaster;
use DB;

class PersediaanMasterController extends Controller
{
    private $setting;
    private $startDate;

    public function __construct() 
    {
        $this->setting = Setting::first();
        $this->startDate = $this->setting->tahun_anggaran . '-01-01';
    }

    private function _getMutasiTambahByDate($tglPembukuan) 
    {
        $query = MutasiTambah::query();
        $query->select(
            'barang_id',
            DB::raw('SUM(jumlah_barang) AS jumlah_barang')
        );
        $query->whereBetween('tgl_pembukuan', [$this->startDate, $tglPembukuan]);
        $query->groupBy('barang_id');

        return $query->get();
    }
    
    public function availableStock(Request $request) 
    {
        $search = $request->query('search');
        $tglPembukuan = $request->query('tgl_pembukuan');

        $mutasiTambah = $this->_getMutasiTambahByDate($tglPembukuan);

        $query = PersediaanMaster::query();
        $query->with(['kodefikasi']);
        $query->whereIn('id', $mutasiTambah->pluck('barang_id'));
        $query->where(function ($q) use ($search) {
            $q->orWhere('nama_barang', 'like', '%'.$search.'%');
            $q->orWhere('spesifikasi', 'like', '%'.$search.'%');
        });
        $query->orderBy('kode_barang');
        
        $data = [];
        foreach ($query->get() as $barang) {
            foreach ($mutasiTambah as $mutasi) {
                if ($mutasi->barang_id == $barang->id) {
                    $barang->jumlah_barang = $mutasi->jumlah_barang;
                }
            }

            array_push($data, $barang);
        }

        return response()->json($data);
    }

    public function findAvailableStock($id) 
    {
        $data = PersediaanMaster::with(['kodefikasi'])->findOrFail($id);

        return response()->json($data);
    }
}
