<?php

namespace App\Http\Controllers\Penyaluran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\ProviderTraits;
use App\Traits\MutasiTraits;
use App\Setting;
use App\PersediaanPenyaluran as Penyaluran;
use App\DokumenUpload;
use App\MutasiTambah;
use App\MutasiKurang;

class SppbController extends Controller
{
    use ProviderTraits, MutasiTraits;

    private $pageTitle;

    public function __construct() 
    {
        $this->pageTitle = 'Surat Perintah Penyaluran Barang (SPPB)';

        $this->middleware('role:1,2,3', ['except' => ['index', 'showByDocs']]);
    }

    private function _getSpbGroupByDate($tglPembukuan) 
    {
        $query = Penyaluran::query();
        $query->select('no_dokumen', 'slug_dokumen', 'tgl_dokumen', 'bidang_id');
        $query->where('kode_pembukuan', '31');
        $query->where('kode_jenis_dokumen', '09');
        $query->whereBetween('tgl_pembukuan', [$this->_startDate(), $tglPembukuan]);
        $query->groupBy('no_dokumen');
        $query->groupBy('slug_dokumen');
        $query->groupBy('tgl_dokumen');
        $query->groupBy('bidang_id');
        $query->orderBy('tgl_pembukuan', 'DESC');

        return $query->get();
    }

    private function _getSpbBySlug($slug) 
    {
        $query = Penyaluran::query();
        $query->where('kode_pembukuan', '31');
        $query->where('kode_jenis_dokumen', '09');
        $query->where('slug_dokumen', $slug);
        $query->orderBy('tgl_pembukuan', 'DESC');

        return $query->get();
    }

    public function index(Request $request) 
    {
        $search = $request->query('search');
        $bidangId = $request->query('bidang_id');

        $query = MutasiKurang::query();
        $query->with(['master_persediaan.kodefikasi', 'get_created_by']);
        $query->where('kode_pembukuan', '31');
        $query->where('kode_jenis_dokumen', '10');
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
                'keperluan',
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
                'bidang_id' => $findDoc['bidang_id'],
                'bidang' => $findDoc['bidang'],
                'total' => collect($item)->sum('jumlah_barang'),
                'data' => $item,
            ];
        })->values();

        return view('penyaluran.sppb.index', [
            'pageTitle' => $this->pageTitle,
            'filter' => [
                'search' => $search,
                'bidang_id' => $bidangId,
            ],
            'total_jumlah_barang' => $query->sum('jumlah_barang'),
            'paginator' => $paginator,
            'data' => $grouped,
        ]);
    }

    public function create(Request $request) 
    {
        $filter = (object)[
            'tgl_pembukuan' => $request->query('tgl_pembukuan', date('Y-m-d')),
            'usulan' => $request->query('usulan'),
        ];

        $dokumenSumber = $this->_getSpbGroupByDate($filter->tgl_pembukuan);
        $dataUsulan = [];

        if (isset($filter->usulan)) {
            foreach ($this->_getSpbBySlug($filter->usulan) as $item) {
                $sisaStok = $this->findPersediaanHasStokTrait($item['barang_id']);
                $item->jumlah_barang_sisa = $sisaStok->stok;

                array_push($dataUsulan, $item);
            }
        }

        return view('penyaluran.sppb.create', [
            'pageTitle' => $this->pageTitle,
            'filter' => $filter,
            'dokumenSumber' => $dokumenSumber,
            'dataUsulan' => $dataUsulan,
        ]);
    }

    public function store(Request $request) 
    {
        $allRequests = $request->all();

        $data = [];
        for ($i=0; $i < count($allRequests['parent_id']); $i++) {
            $jumlahBarangUsulan = $allRequests['jumlah_barang_usulan'][$i];
            $currentStok = $this->findPersediaanHasStokTrait($allRequests['barang_id'][$i]);

            if ($jumlahBarangUsulan > $currentStok->stok) {
                return redirect()->back()->withErrors(['message' => 'Jumlah barang usulan SPB tidak boleh melebihi sisa stok barang.']);
            }

            $hargaSatuan = $this->currentPrice($allRequests['barang_id'][$i]);
            $nilaiPerolehan = $jumlahBarangUsulan * $hargaSatuan;

            $data[] = [
                'kode_pembukuan' => '31',
                'tgl_pembukuan' => $allRequests['tgl_pembukuan'],
                'kode_jenis_dokumen' => '10',
                'no_dokumen' => $allRequests['no_dokumen'],
                'slug_dokumen' => Str::slug($allRequests['no_dokumen'], '-'),
                'tgl_dokumen' => $allRequests['tgl_dokumen'],
                'uraian_dokumen' => $allRequests['uraian_dokumen'],
                'bidang_id' => $allRequests['bidang_id'][$i],
                'barang_id' => $allRequests['barang_id'][$i],
                'jumlah_barang' => $jumlahBarangUsulan,
                'harga_satuan' => $hargaSatuan,
                'nilai_perolehan' => $nilaiPerolehan,
                'saldo_jumlah_barang' => 0,
                'saldo_harga_satuan' => 0,
                'saldo_nilai_perolehan' => 0,
                'keterangan' => $allRequests['keterangan'][$i],
                'dasar_penyaluran_id' => $allRequests['parent_id'][$i],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ];
        }

        foreach ($data as $item) {
            MutasiKurang::create($item);
        }

        return redirect()->route('penyaluran.sppb.index')->with('success', 'Data berhasil disimpan.');
    }

    public function showByDocs($docSlug) 
    {
        $query = MutasiKurang::query();
        $query->with(['master_persediaan.kodefikasi', 'get_created_by']);
        $query->where('kode_pembukuan', '31');
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
                'keperluan',
                'keterangan',
                'get_created_by',
                'created_at',
            ]);
            array_push($collections, $filtered);
        }

        $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
            $findDoc = MutasiKurang::where('slug_dokumen', $key)->first();
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
                'total' => collect($item)->sum('jumlah_barang'),
                'data' => $item,
            ];
        })->values()[0];

        return view('penyaluran.sppb.docs', [
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

        MutasiKurang::where('kode_pembukuan', '31')->where('slug_dokumen', $docSlug)->update($validated);

        return redirect()->route('penyaluran.sppb.showByDocs', $validated['slug_dokumen'])->with('success', 'Dokumen berhasil diupdate.');
    }
}
