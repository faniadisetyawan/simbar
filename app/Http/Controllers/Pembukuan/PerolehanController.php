<?php

namespace App\Http\Controllers\Pembukuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\ProviderTraits;
use App\Http\Requests\Pembukuan\PerolehanRequest;
use Storage;
use App\MutasiTambah;
use App\DokumenUpload;

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
        $query->orderBy('tgl_pembukuan', 'DESC');
        $query->orderBy('bidang_id');
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
            'total_nilai_perolehan' => $query->sum('nilai_perolehan'),
            'total_jumlah_barang' => $query->sum('jumlah_barang'),
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

        // return response()->json($validated);

        $data = new MutasiTambah($validated);
        $data->save();

        return redirect()->route('pembukuan.perolehan.showByDocs', [$slug, $data->slug_dokumen])->with('success', 'Data berhasil disimpan.');
    }

    public function updateBarang(PerolehanRequest $request, $slug, $id) 
    {
        if (! $this->_canUpdatedMutasiTambah($id)) {
            return redirect()->back()->withErrors('Anda tidak bisa melakukan perubahan data karena item ini sudah dilakukan mutasi atau proses lainnya.');
        }

        $validated = $request->validated();
        $validated['harga_satuan'] = ($validated['nilai_perolehan'] / $validated['jumlah_barang']);
        $validated['saldo_jumlah_barang'] = $validated['jumlah_barang'];
        $validated['saldo_harga_satuan'] = $validated['harga_satuan'];
        $validated['saldo_nilai_perolehan'] = $validated['nilai_perolehan'];
        $validated['updated_by'] = auth()->id();

        $data = MutasiTambah::findOrFail($id);
        $data->update($validated);

        return redirect()->back()->with('success', 'Data berhasil diupdate.');
    }

    public function destroyBarang($id) 
    {
        if (! $this->_canUpdatedMutasiTambah($id)) {
            return redirect()->back()->withErrors('Anda tidak bisa melakukan perubahan data karena item ini sudah dilakukan mutasi atau proses lainnya.');
        }
        
        $data = MutasiTambah::findOrFail($id);
        $data->delete();

        $count = MutasiTambah::where('kode_pembukuan', $data->kode_pembukuan)->where('slug_dokumen', $data['slug_dokumen'])->count();
        if ($count === 0) {
            $slug = 'pengadaan';
            if ($data->kode_perolehan == '01') {
                $slug = 'pengadaan';
            } elseif ($data->kode_perolehan == '02') {
                $slug = 'hibah';
            }

            return redirect()->route('pembukuan.perolehan.index', $slug)->with('success', 'Data berhasil dihapus.');
        }

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function showByDocs($slug, $docSlug) 
    {
        $query = MutasiTambah::query();
        $query->with(['master_persediaan.kodefikasi', 'get_created_by']);
        $query->where('slug_dokumen', $docSlug);
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
            $findUpload = DokumenUpload::where('slug_dokumen_tambah', $key)->first();

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
                'upload' => $findUpload,
                'total' => collect($item)->sum('nilai_perolehan'),
                'data' => $item,
            ];
        })->values()[0];

        return view('perolehan.docs', [
            'slug' => $slug,
            'pageTitle' => $this->_pageTitleFromSlug($slug),
            'data' => $grouped,
        ]);
    }

    public function uploadDokumen(Request $request, $slug) 
    {
        $validated = $request->validate([
            'slug_dokumen_tambah' => ['required'],
            'file_upload' => ['required', 'file'],
        ]);
        
        $findDoc = DokumenUpload::where('slug_dokumen_tambah', $validated['slug_dokumen_tambah'])->whereNotNull('file_upload')->first();
        if ($findDoc) {
            $filePath = 'public/dokumen/'.$findDoc->file_upload;
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        }

        if ($request->hasFile('file_upload')) {
            $extension = $request->file_upload->extension();
            $validated['file_upload'] = time().'.'.$extension;

            $request->file('file_upload')->storeAs('public/dokumen', $validated['file_upload']);
        }

        $validated['created_by'] = auth()->id();

        if ($findDoc) {
            $findDoc->update($validated);
        } else {
            $data = new DokumenUpload($validated);
            $data->save();
        }

        return redirect()->back()->with('success', 'Dokumen berhasil diupload.');
    }

    public function updateDoc(Request $request, $slug, $docSlug) 
    {
        $validated = $request->validate([
            'no_dokumen' => ['required'],
            'tgl_dokumen' => ['required', 'date'],
            'uraian_dokumen' => ['nullable'],
        ]);
        $validated['slug_dokumen'] = Str::of($validated['no_dokumen'])->slug('-');

        $kodePerolehan = '';
        if ($slug === 'pengadaan') {
            $kodePerolehan = '01';
        } elseif ($slug === 'hibah') {
            $kodePerolehan = '02';
        }

        MutasiTambah::where('kode_pembukuan', '01')
            ->where('kode_perolehan', $kodePerolehan)
            ->where('slug_dokumen', $docSlug)
            ->update($validated);

        return redirect()->route('pembukuan.perolehan.showByDocs', [$slug, $validated['slug_dokumen']])->with('success', 'Dokumen berhasil diupdate.');
    }
}
