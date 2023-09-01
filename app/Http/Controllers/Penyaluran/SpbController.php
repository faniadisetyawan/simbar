<?php

namespace App\Http\Controllers\Penyaluran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\ProviderTraits;
use App\Traits\MutasiTraits;
use App\Http\Requests\Penyaluran\SpbRequest;
use App\PersediaanPenyaluran as Penyaluran;
use App\DokumenUpload;

class SpbController extends Controller
{
    use ProviderTraits, MutasiTraits;

    private $pageTitle;

    public function __construct() 
    {
        $this->pageTitle = 'Surat Permintaan Barang';

        $this->middleware('role:1,2,3', ['except' => ['index', 'showByDocs']]);
    }

    private function _getNotaPermintaanGroupByDate($tglPembukuan) 
    {
        $query = Penyaluran::query();
        $query->select('no_dokumen', 'slug_dokumen', 'tgl_dokumen', 'bidang_id');
        $query->where('kode_pembukuan', '31');
        $query->where('kode_jenis_dokumen', '08');
        $query->whereBetween('tgl_pembukuan', [$this->_startDate(), $tglPembukuan]);
        $query->groupBy('no_dokumen');
        $query->groupBy('slug_dokumen');
        $query->groupBy('tgl_dokumen');
        $query->groupBy('bidang_id');
        $query->orderBy('tgl_pembukuan', 'DESC');

        return $query->get();
    }

    private function _getNotaPermintaanBySlug($slug) 
    {
        $query = Penyaluran::query();
        $query->where('kode_pembukuan', '31');
        $query->where('kode_jenis_dokumen', '08');
        $query->where('slug_dokumen', $slug);
        $query->orderBy('tgl_pembukuan', 'DESC');

        return $query->get();
    }

    public function index(Request $request) 
    {
        $search = $request->query('search');
        $bidangId = $request->query('bidang_id');

        $query = Penyaluran::query();
        $query->with(['master_persediaan.kodefikasi', 'get_created_by']);
        $query->where('kode_pembukuan', '31');
        $query->where('kode_jenis_dokumen', '09');
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

        return view('penyaluran.spb.index', [
            'pageTitle' => $this->pageTitle,
            'filter' => [
                'search' => $search,
                'bidang_id' => $bidangId,
            ],
            'total_jumlah_barang' => $query->sum('jumlah_barang_usulan'),
            'paginator' => $paginator,
            'data' => $grouped,
        ]);
    }

    public function create(Request $request) 
    {
        $filter = (object)[
            'tgl_pembukuan' => $request->query('tgl_pembukuan', date('Y-m-d')),
            'nota_permintaan' => $request->query('nota_permintaan'),
        ];

        $dokumenNotaPermintaan = $this->_getNotaPermintaanGroupByDate($filter->tgl_pembukuan);
        $dataNotaPermintaan = [];

        if (isset($filter->nota_permintaan)) {
            foreach ($this->_getNotaPermintaanBySlug($filter->nota_permintaan) as $item) {
                $sisaStok = $this->findPersediaanHasStokTrait($item['barang_id']);
                $item->jumlah_barang_sisa = $sisaStok->stok;

                array_push($dataNotaPermintaan, $item);
            }
        }

        return view('penyaluran.spb.create', [
            'pageTitle' => $this->pageTitle,
            'filter' => $filter,
            'dokumenNotaPermintaan' => $dokumenNotaPermintaan,
            'dataNotaPermintaan' => $dataNotaPermintaan,
        ]);
    }

    public function store(Request $request) 
    {
        $allRequests = $request->all();

        $data = [];
        for ($i=0; $i < count($allRequests['parent_id']); $i++) {
            $jumlahBarangUsulan = $allRequests['jumlah_barang_usulan'][$i];

            $currentStok = $this->findPersediaanHasStokTrait($allRequests['barang_id'][$i]);
            $sisaStok = ($currentStok->stok - $jumlahBarangUsulan);

            if ($jumlahBarangUsulan > $currentStok) {
                return redirect()->back()->withErrors(['message' => 'Jumlah barang usulan SPB tidak boleh melebihi sisa stok barang.']);
            }

            $data[] = [
                'kode_pembukuan' => '31',
                'tgl_pembukuan' => $allRequests['tgl_pembukuan'],
                'kode_jenis_dokumen' => '09',
                'no_dokumen' => $allRequests['no_dokumen'],
                'slug_dokumen' => Str::slug($allRequests['no_dokumen'], '-'),
                'tgl_dokumen' => $allRequests['tgl_dokumen'],
                'uraian_dokumen' => $allRequests['uraian_dokumen'],
                'bidang_id' => $allRequests['bidang_id'][$i],
                'barang_id' => $allRequests['barang_id'][$i],
                'jumlah_barang_permintaan' => $allRequests['jumlah_barang_permintaan'][$i],
                'jumlah_barang_sisa' => $sisaStok,
                'jumlah_barang_usulan' => $jumlahBarangUsulan,
                'keperluan' => $allRequests['keperluan'][$i],
                'keterangan' => $allRequests['keterangan'][$i],
                'parent_id' => $allRequests['parent_id'][$i],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ];
        }

        foreach ($data as $item) {
            Penyaluran::create($item);
        }

        return redirect()->route('penyaluran.spb.showByDocs', $data[0]['slug_dokumen'])->with('success', 'Data berhasil disimpan.');
    }

    public function showByDocs($docSlug) 
    {
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
                'total' => collect($item)->sum('jumlah_barang_usulan'),
                'data' => $item,
            ];
        })->values()[0];

        return view('penyaluran.spb.docs', [
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

        Penyaluran::where('slug_dokumen', $docSlug)->where('kode_jenis_dokumen', '09')->update($validated);

        return redirect()->route('penyaluran.spb.showByDocs', $validated['slug_dokumen'])->with('success', 'Dokumen berhasil diupdate.');
    }
}
