<?php

namespace App\Http\Controllers\Pembukuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\MutasiTraits;
use App\PersediaanOpname;
use App\PersediaanMaster;
use App\MutasiTambah;
use App\MutasiKurang;
use App\DokumenUpload;

class StockOpnameController extends Controller
{
    use MutasiTraits;

    private $pageTitle;
    private $kodePembukuan;
    private $kodeJenisDokumen;

    public function __construct() 
    {
        $this->pageTitle = 'Stock Opname';
        $this->kodePembukuan = '32';
        $this->kodeJenisDokumen = '99';
    }

    public function index(Request $request) 
    {
        $filter = (object)[
            'search' => $request->query('search'),
        ];

        $query = PersediaanOpname::query();
        $query->with(['master_persediaan.kodefikasi']);
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
                'keterangan',
                'created_at',
            ]);
            array_push($collections, $filtered);
        }

        $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
            $findDoc = PersediaanOpname::where('slug_dokumen', $key)->first();

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

        return view('stock-opname.index', [
            'pageTitle' => $this->pageTitle,
            'filter' => $filter,
            'paginator' => $paginator,
            'data' => $grouped,
        ]);
    }

    public function create() 
    {
        return view('stock-opname.create', [
            'pageTitle' => $this->pageTitle,
            'props' => NULL,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_pembukuan' => ['required', 'date'],
            'no_dokumen' => ['required'],
            'tgl_dokumen' => ['required', 'date'],
            'uraian_dokumen' => ['nullable'],
            'barang_id' => ['required'],
            'jumlah_barang' => ['required', 'gte:0'],
            'keterangan' => ['nullable'],
        ]);
        $validated['kode_pembukuan'] = $this->kodePembukuan;
        $validated['kode_jenis_dokumen'] = $this->kodeJenisDokumen;
        $validated['slug_dokumen'] = Str::slug($validated['no_dokumen'], '-');
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $saved = PersediaanOpname::create($validated);

        $findBarang = PersediaanMaster::findOrFail($validated['barang_id']);
        if ($findBarang->stok > $validated['jumlah_barang']) { // data greater than real
            $jumlahBarang = $findBarang->stok - $validated['jumlah_barang'];
            $hargaSatuan = $this->currentPrice($validated['barang_id']);
            $nilaiPerolehan = $jumlahBarang * $hargaSatuan;

            $validated['jumlah_barang'] = $jumlahBarang;
            $validated['harga_satuan'] = $hargaSatuan;
            $validated['nilai_perolehan'] = $nilaiPerolehan;
            $validated['opname_id'] = $saved->id;

            MutasiKurang::create($validated);
        } else {
            $jumlahBarang = $validated['jumlah_barang'] - $findBarang->stok;
            $hargaSatuan = $this->currentPrice($validated['barang_id']);
            $nilaiPerolehan = $jumlahBarang * $hargaSatuan;

            $validated['jumlah_barang'] = $jumlahBarang;
            $validated['harga_satuan'] = $hargaSatuan;
            $validated['nilai_perolehan'] = $nilaiPerolehan;
            $validated['opname_id'] = $saved->id;

            MutasiTambah::create($validated);
        }

        return redirect()->route('pembukuan.stock-opname.showByDocs', $validated['slug_dokumen'])->with('success', 'Data berhasil ditambahkan.');
    }

    public function destroyBarang($id) 
    {
        $data = PersediaanOpname::findOrFail($id);
        $data->delete();

        $count = PersediaanOpname::where('slug_dokumen', $data['slug_dokumen'])->count();
        if ($count === 0) {
            return redirect()->route('pembukuan.stock-opname.index')->with('success', 'Data berhasil dihapus.');
        }

        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function showByDocs($docSlug)
    {
        $query = PersediaanOpname::query();
        $query->with(['master_persediaan.kodefikasi']);
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
                'keterangan',
                'created_at',
            ]);
            array_push($collections, $filtered);
        }

        $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
            $findDoc = PersediaanOpname::where('slug_dokumen', $key)->first();
            $findUpload = DokumenUpload::where('slug_dokumen_kurang', $key)->first();

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

        return view('stock-opname.docs', [
            'pageTitle' => $this->pageTitle,
            'data' => $grouped,
        ]);
    }
}
