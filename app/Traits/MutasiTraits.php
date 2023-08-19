<?php

namespace App\Traits;

use DB;
use App\PersediaanMaster;
use App\MutasiTambah;
use App\MutasiKurang;

trait MutasiTraits
{
    private function _getMutasiTambahByDate($tglPembukuan, $barangId) 
    {
        $query = MutasiTambah::query();
        $query->with(['pembukuan']);
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
        $query->whereBetween('tgl_pembukuan', [$this->_startDate(), $tglPembukuan]);
        $query->orderBy('tgl_pembukuan');

        return $query;
    }

    private function _getMutasiKurangByDate($tglPembukuan, $barangId)
    {
        $query = MutasiKurang::query();
        $query->with(['pembukuan']);
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
        $query->whereBetween('tgl_pembukuan', [$this->_startDate(), $tglPembukuan]);
        $query->orderBy('tgl_pembukuan');

        return $query;
    }

    private function _getSumSaldoAwalByDate($tglPembukuan, $barangId) 
    {
        return MutasiTambah::where('kode_pembukuan', '01')
            ->where('kode_perolehan', '00')
            ->where('barang_id', $barangId)
            ->whereBetween('tgl_pembukuan', [$this->_startDate(), $tglPembukuan])
            ->sum('jumlah_barang');
    }

    private function _getSumMutasiTambahByDate($tglPembukuan, $barangId) 
    {
        return MutasiTambah::whereIn('kode_pembukuan', ['01', '32'])
            ->where('kode_perolehan', '!=', '00')
            ->where('barang_id', $barangId)
            ->whereBetween('tgl_pembukuan', [$this->_startDate(), $tglPembukuan])
            ->sum('jumlah_barang');
    }

    private function _getSumMutasiKurangByDate($tglPembukuan, $barangId) 
    {
        return MutasiKurang::whereIn('kode_pembukuan', ['14', '31', '32'])
            ->where('barang_id', $barangId)
            ->whereBetween('tgl_pembukuan', [$this->_startDate(), $tglPembukuan])
            ->sum('jumlah_barang');
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
                    'pembukuan' => $value->pembukuan,
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
            $newObj->pembukuan = $mutasi->pembukuan;

            foreach ($breakdown as $keyItem => $item) {
                if (($item->used - 1) == $keyMutasi) {
                    $newObj->nilai_perolehan += $item->harga_satuan;
                }
            }

            array_push($data, $newObj);
        }

        return $data;
    }

    public function logMutasiTrait($tglPembukuan, $barangId) 
    {
        $sourceMutasiTambah = $this->_getMutasiTambahByDate($tglPembukuan, $barangId)->get();
        $sourceMutasiKurang = $this->_getMutasiKurangByDate($tglPembukuan, $barangId)->get();

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
            $newObj->pembukuan = $item->pembukuan;

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
            $newObj->pembukuan = $item->pembukuan;

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

        return $data;
    }

    public function persediaanHasStokTrait() 
    {
        $mutasiTambah = MutasiTambah::select('barang_id')->groupBy('barang_id')->get();

        $query = PersediaanMaster::query();
        $query->with(['kodefikasi']);
        $query->whereIn('id', $mutasiTambah->pluck('barang_id'));
        $query->orderBy('kode_barang');
        $collections = $query->get();

        $grouped = collect($collections)->where('stok', '>', 0)->groupBy('kode_barang')->map(function ($item, $key) {
            return (object)[
                'key' => $item[0]['kodefikasi'],
                'data' => $item,
            ];
        })->values();

        return $grouped;
    }

    public function findPersediaanHasStokTrait($barangId) 
    {
        return PersediaanMaster::with(['kodefikasi'])->findOrFail($barangId);
    }
}