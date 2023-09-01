<?php

namespace App\Http\Controllers\Pembukuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;
use App\Traits\MutasiTraits;
use App\MutasiKurang;
use App\DokumenUpload;

class ReklasifikasiController extends Controller
{
    use MutasiTraits;

    private $pageTitle;
    private $kodePembukuan;

    public function __construct()
    {
        $this->pageTitle = 'Reklasifikasi';
        $this->kodePembukuan = '06';

        $this->middleware('role:1,2,3', ['except' => ['index', 'showByDocs']]);
    }

    public function index(Request $request)
    {
        $filter = (object)[
            'search' => $request->query('search'),
        ];

        $query = MutasiKurang::query();
        $query->with(['master_persediaan.kodefikasi', 'get_created_by']);
        $query->where('kode_pembukuan', $this->kodePembukuan);
        $query->orderBy('tgl_pembukuan', 'DESC');
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
                'keterangan',
                'get_created_by',
                'created_at',
            ]);
            array_push($collections, $filtered);
        }

        $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
            $findDoc = MutasiKurang::where('slug_dokumen', $key)->first();

            return [
                'kode_pembukuan' => $findDoc['kode_pembukuan'],
                'kode_perolehan' => $findDoc['kode_perolehan'],
                'kode_jenis_dokumen' => $findDoc['kode_jenis_dokumen'],
                'no_dokumen' => $findDoc['no_dokumen'],
                'slug_dokumen' => $findDoc['slug_dokumen'],
                'tgl_dokumen' => $findDoc['tgl_dokumen'],
                'uraian_dokumen' => $findDoc['uraian_dokumen'],
                'bidang' => $findDoc['bidang'],
                'total' => collect($item)->sum('nilai_perolehan'),
                'data' => $item,
            ];
        })->values();

        return view('reklasifikasi.index', [
            'pageTitle' => $this->pageTitle,
            'filter' => $filter,
            'paginator' => $paginator,
            'data' => $grouped,
        ]);
    }

    public function create()
    {
        return view('reklasifikasi.create', [
            'pageTitle' => $this->pageTitle,
            'props' => NULL,
        ]);
    }

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'tgl_pembukuan' => ['required', 'date'],
            'kode_jenis_dokumen' => ['required'],
            'no_dokumen' => ['required'],
            'tgl_dokumen' => ['required', 'date'],
            'uraian_dokumen' => ['nullable'],
            'barang_id' => ['required'],
            'jumlah_barang' => ['required', 'gt:0'],
            'keterangan' => ['nullable'],
        ]);
        $hargaSatuan = $this->currentPrice($validated['barang_id']);
        $nilaiPerolehan = $validated['jumlah_barang'] * $hargaSatuan;

        $validated['kode_pembukuan'] = $this->kodePembukuan;
        $validated['slug_dokumen'] = Str::of($validated['no_dokumen'])->slug('-');
        $validated['harga_satuan'] = $hargaSatuan;
        $validated['nilai_perolehan'] = $nilaiPerolehan;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        MutasiKurang::create($validated);

        return redirect()->route('pembukuan.reklasifikasi.showByDocs', $validated['slug_dokumen'])->with('success', 'Data berhasil ditambahkan.');
    }

    public function updateBarang(Request $request, $id) 
    {
        $validated = $request->validate([
            'tgl_pembukuan' => ['required', 'date'],
            'barang_id' => ['required'],
            'jumlah_barang' => ['required', 'gt:0'],
            'keterangan' => ['nullable'],
        ]);
        $validated['updated_by'] = auth()->id();

        MutasiKurang::findOrFail($id)->update($validated);

        return back()->with('success', 'Data berhasil diupdate.');
    }

    public function destroyBarang($id) 
    {
        $data = MutasiKurang::findOrFail($id);
        $data->delete();

        $count = MutasiKurang::where('kode_pembukuan', $this->kodePembukuan)->where('slug_dokumen', $data['slug_dokumen'])->count();
        if ($count === 0) {
            return redirect()->route('pembukuan.reklasifikasi.index')->with('success', 'Data berhasil dihapus.');
        }

        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function showByDocs($docSlug)
    {
        $query = MutasiKurang::query();
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
                'keterangan',
                'get_created_by',
                'created_at',
            ]);
            array_push($collections, $filtered);
        }

        $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
            $findDoc = MutasiKurang::where('slug_dokumen', $key)->first();
            $findUpload = DokumenUpload::where('slug_dokumen_kurang', $key)->first();

            return [
                'kode_pembukuan' => $findDoc['kode_pembukuan'],
                'kode_perolehan' => $findDoc['kode_perolehan'],
                'kode_jenis_dokumen' => $findDoc['kode_jenis_dokumen'],
                'no_dokumen' => $findDoc['no_dokumen'],
                'slug_dokumen' => $findDoc['slug_dokumen'],
                'tgl_dokumen' => $findDoc['tgl_dokumen'],
                'uraian_dokumen' => $findDoc['uraian_dokumen'],
                'bidang' => $findDoc['bidang'],
                'upload' => $findUpload,
                'total' => collect($item)->sum('nilai_perolehan'),
                'data' => $item,
            ];
        })->values()[0];

        return view('reklasifikasi.docs', [
            'pageTitle' => $this->pageTitle,
            'data' => $grouped,
        ]);
    }

    public function uploadDokumen(Request $request) 
    {
        $validated = $request->validate([
            'slug_dokumen_kurang' => ['required'],
            'file_upload' => ['required', 'file'],
        ]);
        
        $findDoc = DokumenUpload::where('slug_dokumen_kurang', $validated['slug_dokumen_kurang'])->whereNotNull('file_upload')->first();
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
            DokumenUpload::create($validated);
        }

        return redirect()->back()->with('success', 'Dokumen berhasil diupload.');
    }

    public function updateDoc(Request $request, $docSlug) 
    {
        $validated = $request->validate([
            'no_dokumen' => ['required'],
            'tgl_dokumen' => ['required', 'date'],
            'uraian_dokumen' => ['nullable'],
        ]);
        $validated['slug_dokumen'] = Str::of($validated['no_dokumen'])->slug('-');

        MutasiKurang::where('kode_pembukuan', $this->kodePembukuan)
            ->where('slug_dokumen', $docSlug)
            ->update($validated);

        return redirect()->route('pembukuan.reklasifikasi.showByDocs', $validated['slug_dokumen'])->with('success', 'Dokumen berhasil diupdate.');
    }
}
