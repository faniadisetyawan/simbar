<?php

namespace App\Http\Controllers\Penyaluran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Penyaluran\NotaPermintaanRequest;
use App\PersediaanPenyaluran as Penyaluran;
use App\DokumenUpload;

class NotaPermintaanController extends Controller
{
    private $pageTitle;

    public function __construct() 
    {
        $this->pageTitle = 'Nota Permintaan Barang';
    }

    public function index(Request $request) 
    {
        $search = $request->query('search');
        $bidangId = $request->query('bidang_id');

        $query = Penyaluran::query();
        $query->with(['master_persediaan.kodefikasi', 'get_created_by']);
        $query->where('kode_pembukuan', '31');
        $query->where('kode_jenis_dokumen', '08');
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
                'jumlah_barang_permintaan',
                'keperluan',
                'keterangan',
                'get_created_by',
                'created_at',
            ]);
            array_push($collections, $filtered);
        }

        $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
            $findDoc = Penyaluran::where('slug_dokumen', $key)->first();

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
                'total' => collect($item)->sum('jumlah_barang_permintaan'),
                'data' => $item,
            ];
        })->values();

        return view('penyaluran.nota-permintaan.index', [
            'pageTitle' => $this->pageTitle,
            'filter' => [
                'search' => $search,
                'bidang_id' => $bidangId,
            ],
            'total_jumlah_barang' => $query->sum('jumlah_barang_permintaan'),
            'paginator' => $paginator,
            'data' => $grouped,
        ]);
    }

    public function create() 
    {
        return view('penyaluran.nota-permintaan.create', [
            'pageTitle' => $this->pageTitle,
        ]);
    }

    public function store(NotaPermintaanRequest $request) 
    {
        $validated = $request->validated();
        $validated['kode_pembukuan'] = '31';
        $validated['kode_jenis_dokumen'] = '08';
        $validated['slug_dokumen'] = Str::slug($validated['no_dokumen'], '-');
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $data = new Penyaluran($validated);
        $data->save();

        return redirect()->route('penyaluran.nota-permintaan.showByDocs', $data->slug_dokumen)->with('success', 'Data berhasil ditambahkan.');
    }

    public function updateBarang(NotaPermintaanRequest $request, $id) 
    {
        $validated = $request->validated();
        $validated['updated_by'] = auth()->id();

        $data = Penyaluran::findOrFail($id);
        $data->update($validated);

        return redirect()->back()->with('success', 'Data berhasil diupdate.');
    }

    public function destroyBarang($id) 
    {
        $data = Penyaluran::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function showByDocs(Request $request, $docSlug) 
    {
        $print = $request->boolean('print');

        $query = Penyaluran::query();
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
                'jumlah_barang_permintaan',
                'jumlah_barang_sisa',
                'jumlah_barang_usulan',
                'keperluan',
                'keterangan',
                'get_created_by',
                'created_at',
            ]);
            array_push($collections, $filtered);
        }

        $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
            $findDoc = Penyaluran::where('slug_dokumen', $key)->first();
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
                'total' => collect($item)->sum('jumlah_barang_permintaan'),
                'data' => $item,
            ];
        })->values()[0];

        if (isset($print) && $print === TRUE) {
            return view('penyaluran.nota-permintaan.print', [
                'pageTitle' => $this->pageTitle,
                'data' => $grouped,
            ]);
        }

        return view('penyaluran.nota-permintaan.docs', [
            'pageTitle' => $this->pageTitle,
            'data' => $grouped,
        ]);
    }

    public function updateDoc(Request $request, $docSlug) 
    {
        $validated = $request->validate([
            'no_dokumen' => ['required'],
            'tgl_dokumen' => ['required', 'date'],
            'uraian_dokumen' => ['nullable'],
        ]);
        $validated['slug_dokumen'] = Str::of($validated['no_dokumen'])->slug('-');

        Penyaluran::where('slug_dokumen', $docSlug)->where('kode_jenis_dokumen', '08')->update($validated);

        return redirect()->route('penyaluran.nota-permintaan.showByDocs', $validated['slug_dokumen'])->with('success', 'Dokumen berhasil diupdate.');
    }
}
