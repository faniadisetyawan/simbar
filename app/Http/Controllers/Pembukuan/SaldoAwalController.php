<?php

namespace App\Http\Controllers\Pembukuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Traits\ProviderTraits;
use App\Http\Requests\SaldoAwal\PersediaanRequest;
use App\PersediaanMaster;
use App\MutasiTambah;

class SaldoAwalController extends Controller
{
    use ProviderTraits;

    public function index(Request $request, $slug) 
    {
        $search = $request->query('search');
        $active = $request->boolean('active', TRUE);
        
        $data = NULL;
        $total = 0;

        if ($slug === 'persediaan') {
            $query = MutasiTambah::query();
            $query->where('kode_pembukuan', '01');
            $query->where('kode_perolehan', '00');
            $query->where(DB::raw('YEAR(tgl_pembukuan)'), date('Y'));
            $query->whereHas('master_persediaan', function ($qMaster) use ($search) {
                $qMaster->where(function ($qm) use ($search) {
                    $qm->orWhere('kode_barang', 'like', '%'.$search.'%');
                    $qm->orWhere('kode_register', 'like', '%'.$search.'%');
                    $qm->orWhere('nama_barang', 'like', '%'.$search.'%');
                    $qm->orWhere('spesifikasi', 'like', '%'.$search.'%');
                });
            });

            $total = $query->sum('nilai_perolehan');
            $data = $query->paginate(25);
        }

        return view('saldo-awal.index', [
            'slug' => $slug,
            'pageTitle' => $this->_pageTitleFromSlug($slug),
            'filter' => [
                'search' => $search,
                'active' => $active,
            ],
            'total' => $total,
            'data' => $data,
        ]);
    }

    public function create($slug) 
    {
        if ($slug === 'persediaan') {
            return $this->_createPersediaan($slug);
        } else {
            return $this->_createAsetTetap($slug);
        }
    }

    private function _createPersediaan($slug) 
    {
        $barang = PersediaanMaster::get();

        $groupedBarang = collect($barang)->groupBy('kodefikasi.concat')->map(function ($item, $key) {
            return [
                'key' => $key,
                'data' => $item,
            ];
        })->values();

        return view('saldo-awal.form', [
            'slug' => $slug,
            'pageTitle' => $this->_pageTitleFromSlug($slug),
            'barang' => $groupedBarang,
            'props' => NULL,
        ]);
    }

    private function _createAsetTetap($slug) 
    {
        return view('saldo-awal.form', [
            'slug' => $slug,
            'pageTitle' => $this->_pageTitleFromSlug($slug),
            'barang' => [],
            'props' => NULL,
        ]);
    }

    public function store(PersediaanRequest $request) 
    {
        $validated = $request->validated();
        $validated['kode_pembukuan'] = '01';
        $validated['kode_perolehan'] = '00';
        $validated['tgl_pembukuan'] = date('Y') . '-01-01';
        $validated['kode_jenis_dokumen'] = '99';
        $validated['harga_satuan'] = $validated['nilai_perolehan'] / $validated['jumlah_barang'];
        $validated['saldo_jumlah_barang'] = $validated['jumlah_barang'];
        $validated['saldo_harga_satuan'] = $validated['harga_satuan'];
        $validated['saldo_nilai_perolehan'] = $validated['nilai_perolehan'];
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $data = new MutasiTambah($validated);
        $data->save();

        return redirect()->back()->with('success', 'Item berhasil ditambahkan.');
    }
}
