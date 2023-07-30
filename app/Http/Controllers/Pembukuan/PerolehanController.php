<?php

namespace App\Http\Controllers\Pembukuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\ProviderTraits;
use App\Http\Requests\Pembukuan\PerolehanRequest;
use App\MutasiTambah;

class PerolehanController extends Controller
{
    use ProviderTraits;

    public function index(Request $request, $slug) 
    {
        $search = $request->query('search');
        $bidangId = $request->query('bidang_id');

        $kodePerolehan = NULL;
        switch ($slug) {
            case 'pengadaan': $kodePerolehan = '01'; break;
            case 'hibah': $kodePerolehan = '02'; break;
            default: $kodePerolehan = NULL; break;
        }

        $query = MutasiTambah::query();
        $query->with(['master_persediaan.kodefikasi', 'get_created_by']);
        $query->where('kode_pembukuan', '01');
        $query->where('kode_perolehan', $kodePerolehan);
        $query->when(isset($bidangId), function ($sub) use ($bidangId) {
            $sub->where('bidang_id', $bidangId);
        });
        $paginator = $query->paginate(25);

        $collections = [];
        foreach ($paginator as $item) {
            $collection = collect($item);
            $filtered = $collection->only([
                'id',
                'tgl_pembukuan',
                'no_dokumen',
                'slug_dokumen',
                'barang_id',
                'master_persediaan',
                'jumlah_barang',
                'harga_satuan',
                'nilai_perolehan',
                'tgl_expired',
                'keterangan',
                'get_created_by',
                'created_at',
            ]);
            array_push($collections, $filtered);
        }

        $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
            $findDoc = MutasiTambah::where('slug_dokumen', $key)->first();

            return [
                'kode_pembukuan' => $findDoc['kode_pembukuan'],
                'kode_perolehan' => $findDoc['kode_perolehan'],
                'kode_jenis_dokumen' => $findDoc['kode_jenis_dokumen'],
                'no_dokumen' => $findDoc['no_dokumen'],
                'slug_dokumen' => $findDoc['slug_dokumen'],
                'tgl_dokumen' => $findDoc['tgl_dokumen'],
                'uraian_dokumen' => $findDoc['uraian_dokumen'],
                'bidang_id' => $findDoc['bidang_id'],
                'bidang' => $findDoc['bidang'],
                'total' => collect($item)->sum('nilai_perolehan'),
                'data' => $item,
            ];
        })->values();

        return view('perolehan.index', [
            'slug' => $slug,
            'pageTitle' => $this->_pageTitleFromSlug($slug),
            'filter' => [
                'search' => $search,
                'bidang_id' => $bidangId,
            ],
            'paginator' => $paginator,
            'data' => $grouped,
        ]);
    }

    public function create($slug) 
    {
        return view('perolehan.form', [
            'slug' => $slug,
            'pageTitle' => $this->_pageTitleFromSlug($slug),
            'props' => NULL,
        ]);
    }

    public function store(PerolehanRequest $request, $slug) 
    {
        $kodePerolehan = NULL;
        switch ($slug) {
            case 'pengadaan': $kodePerolehan = '01'; break;
            case 'hibah': $kodePerolehan = '02'; break;
            default: $kodePerolehan = NULL; break;
        }

        $validated = $request->validated();
        $validated['kode_pembukuan'] = '01';
        $validated['kode_perolehan'] = $kodePerolehan;
        $validated['slug_dokumen'] = Str::slug($validated['no_dokumen'], '-');
        $validated['harga_satuan'] = ($validated['nilai_perolehan'] / $validated['jumlah_barang']);
        $validated['saldo_jumlah_barang'] = $validated['jumlah_barang'];
        $validated['saldo_harga_satuan'] = $validated['harga_satuan'];
        $validated['saldo_nilai_perolehan'] = $validated['nilai_perolehan'];
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $data = new MutasiTambah($validated);
        $data->save();

        return redirect()->route('pembukuan.perolehan.showByDocs', [$slug, $data->slug_dokumen])->with('success', 'Data berhasil disimpan.');
    }

    public function showByDocs($slug, $docSlug) 
    {
        $query = MutasiTambah::query();
        $query->with(['master_persediaan.kodefikasi', 'get_created_by']);
        $query->where('slug_dokumen', $docSlug)->get();
        $results = $query->get();

        $collections = [];
        foreach ($results as $item) {
            $collection = collect($item);
            $filtered = $collection->only([
                'id',
                'tgl_pembukuan',
                'no_dokumen',
                'slug_dokumen',
                'barang_id',
                'master_persediaan',
                'jumlah_barang',
                'harga_satuan',
                'nilai_perolehan',
                'tgl_expired',
                'keterangan',
                'get_created_by',
                'created_at',
            ]);
            array_push($collections, $filtered);
        }

        $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
            $findDoc = MutasiTambah::where('slug_dokumen', $key)->first();

            return [
                'kode_pembukuan' => $findDoc['kode_pembukuan'],
                'kode_perolehan' => $findDoc['kode_perolehan'],
                'kode_jenis_dokumen' => $findDoc['kode_jenis_dokumen'],
                'no_dokumen' => $findDoc['no_dokumen'],
                'slug_dokumen' => $findDoc['slug_dokumen'],
                'tgl_dokumen' => $findDoc['tgl_dokumen'],
                'uraian_dokumen' => $findDoc['uraian_dokumen'],
                'bidang_id' => $findDoc['bidang_id'],
                'bidang' => $findDoc['bidang'],
                'total' => collect($item)->sum('nilai_perolehan'),
                'data' => $item,
            ];
        })->values()[0];

        // return response()->json($grouped);

        return view('perolehan.docs', [
            'slug' => $slug,
            'pageTitle' => $this->_pageTitleFromSlug($slug),
            'data' => $grouped,
        ]);
    }
}
