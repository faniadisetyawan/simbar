<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\MutasiTraits;
use DB;
use App\Setting;
use App\MutasiTambah;
use App\MutasiKurang;
use App\PersediaanMaster;

class PersediaanMasterController extends Controller
{
    use MutasiTraits;

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

    public function hasStok(Request $request) 
    {
        $search = $request->query('search');

        $collections = $this->persediaanHasStokTrait($search);

        $data = collect($collections)->where('stok', '>', 0)->values();

        return response()->json($data);
    }

    
}
