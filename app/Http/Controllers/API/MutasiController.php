<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ProviderTraits;
use DB;
use App\Setting;
use App\PersediaanMaster;
use App\MutasiTambah;
use App\MutasiKurang;

class MutasiController extends Controller
{
    use ProviderTraits;

    private function _getMutasiTambah($tglPembukuan, $barangId) 
    {
        $query = MutasiTambah::query();
        $query->select(
            'kode_pembukuan',
            'tgl_pembukuan',
            'barang_id',
            'jumlah_barang',
            'harga_satuan',
            'nilai_perolehan',
            DB::raw('TRUE AS is_tambah')
        );
        $query->where('barang_id', $barangId);
        $query->whereBetween('tgl_pembukuan', [$this->startDate, $tglPembukuan]);
        $query->orderBy('tgl_pembukuan');

        return $query;
    }

    private function _getMutasiKurang($tglPembukuan, $barangId)
    {
        $query = MutasiKurang::query();
        $query->select(
            'kode_pembukuan',
            'tgl_pembukuan',
            'barang_id',
            'jumlah_barang',
            'harga_satuan',
            'nilai_perolehan',
            DB::raw('FALSE AS is_tambah')
        );
        $query->where('barang_id', $barangId);
        $query->whereBetween('tgl_pembukuan', [$this->startDate, $tglPembukuan]);
        $query->orderBy('tgl_pembukuan');

        return $query;
    }

    private function _groupedMutasiKurang($arrayMutasiTambah, $arrayMutasiKurang) 
    {
        $breakdown = [];
        foreach ($arrayMutasiTambah as $key => $value) {
            for ($i=0; $i < $value->jumlah_barang; $i++) {
                array_push($breakdown, (object)[
                    'kode_pembukuan' => $value->kode_pembukuan,
                    'tgl_pembukuan' => $value->tgl_pembukuan,
                    'barang_id' => $value->barang_id,
                    'jumlah_barang' => 1,
                    'harga_satuan' => $value->harga_satuan,
                    'used' => 0,
                ]);
            }
        }

        $startRow = 0;
        $endRow = 0;
        foreach ($arrayMutasiKurang as $key => $mutasi) {
            $usedBy = $key + 1;

            if ($key === 0) {
                $endRow = $mutasi->jumlah_barang;
            } else {
                $endRow = $arrayMutasiKurang[$key - 1]->jumlah_barang + $arrayMutasiKurang[$key]->jumlah_barang;
            }

            for ($i=$startRow; $i < $endRow; $i++) { 
                $breakdown[$i]->used = $usedBy;
                $startRow++;
            }
        }

        $data = [];
        foreach ($arrayMutasiKurang as $keyMutasi => $mutasi) {
            $newObj = (object)[];
            $newObj->kode_pembukuan = $mutasi->kode_pembukuan;
            $newObj->tgl_pembukuan = $mutasi->tgl_pembukuan;
            $newObj->barang_id = $mutasi->barang_id;
            $newObj->jumlah_barang = $mutasi->jumlah_barang;
            $newObj->nilai_perolehan = 0;
            $newObj->is_tambah = $mutasi->is_tambah;

            foreach ($breakdown as $keyItem => $item) {
                if (($item->used - 1) == $keyMutasi) {
                    $newObj->nilai_perolehan += $item->harga_satuan;
                }
            }

            array_push($data, $newObj);
        }

        return $data;
    }

    public function kartuPersediaan(Request $request) 
    {
        $tglPembukuan = $request->query('tgl_pembukuan');
        $barangId = $request->query('barang_id');

        $sourceMutasiTambah = $this->_getMutasiTambah($tglPembukuan, $barangId)->get();
        $sourceMutasiKurang = $this->_getMutasiKurang($tglPembukuan, $barangId)->get();

        $groupedMutasiKurang = $this->_groupedMutasiKurang($sourceMutasiTambah, $sourceMutasiKurang);
        $collection = array_merge($sourceMutasiTambah->toArray(), $groupedMutasiKurang);
        $grouped = collect($collection)->sortBy('tgl_pembukuan')->values()->all();

        $items = [];
        foreach ($grouped as $key => $value) {
            $item = (object)$value;

            $newObj = (object)[];
            $newObj->kode_pembukuan = $item->kode_pembukuan;
            $newObj->tgl_pembukuan = $item->tgl_pembukuan;
            $newObj->barang_id = $item->barang_id;
            $newObj->jumlah_barang = $item->jumlah_barang;
            $newObj->harga_satuan = isset($item->harga_satuan) ? $item->harga_satuan : 0;
            $newObj->nilai_perolehan = $item->nilai_perolehan;
            $newObj->is_tambah = $item->is_tambah;
            $newObj->stok = 0;
            $newObj->nilai_akhir = 0;

            array_push($items, $newObj);
        }

        $data = [];
        foreach ($items as $key => $item) {
            $newObj = (object)[];
            $newObj->kode_pembukuan = $item->kode_pembukuan;
            $newObj->tgl_pembukuan = $item->tgl_pembukuan;
            $newObj->barang_id = $item->barang_id;
            $newObj->jumlah_barang = $item->jumlah_barang;
            $newObj->harga_satuan = $item->harga_satuan;
            $newObj->nilai_perolehan = $item->nilai_perolehan;
            $newObj->is_tambah = $item->is_tambah;
            $newObj->stok = 0;
            $newObj->nilai_akhir = 0;

            if ($key === 0) {
                $newObj->stok = $item->jumlah_barang;
                $newObj->nilai_akhir = $item->nilai_perolehan;
            } else {
                if ($item->is_tambah) {
                    $newObj->stok = $data[$key - 1]->stok + $item->jumlah_barang;
                    $newObj->nilai_akhir = $data[$key - 1]->nilai_akhir + $item->nilai_perolehan;
                } else {
                    $newObj->stok = $data[$key - 1]->stok - $item->jumlah_barang;
                    $newObj->nilai_akhir = $data[$key - 1]->nilai_akhir - $item->nilai_perolehan;
                }
            }

            array_push($data, $newObj);
        }

        return response()->json($data);
    }

    public function currentPrice($id) 
    {
        $master = PersediaanMaster::findOrFail($id);
        $mutasiTambah = MutasiTambah::where('barang_id', $id)->orderBy('tgl_pembukuan', 'ASC')->get();
        $mutasiKurang = MutasiKurang::where('barang_id', $id)->orderBy('tgl_pembukuan', 'ASC')->get();

        $i = 0;
        $stop = 0;
        $totalMT = $mutasiKurang->sum('jumlah_barang');
        $latest = 0;

        while ($stop < $totalMT) {
            $stop += $mutasiTambah[$i]->jumlah_barang;
            $latest = $mutasiTambah[$i];
            $i++;
        }

        return response()->json($latest->harga_satuan);
    }
}
