<?php

namespace App\Http\Controllers\Pembukuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\ProviderTraits;
use App\Imports\SaldoAwal\PersediaanImport;
use App\Imports\SaldoAwal\TanahImport;
use App\Imports\SaldoAwal\PeralatanMesinImport;
use App\Imports\SaldoAwal\GedungBangunanImport;
use App\Imports\SaldoAwal\JIJImport;
use App\Imports\SaldoAwal\ATLImport;
use App\Imports\SaldoAwal\KDPImport;
use App\Imports\SaldoAwal\ATBImport;
use App\Http\Requests\SaldoAwal\PersediaanRequest;
use App\KodefikasiSubSubRincianObjek;
use App\MutasiTambah;
use App\Barang;

class SaldoAwalController extends Controller
{
    use ProviderTraits;

    public function index(Request $request, $slug) 
    {
        $search = $request->query('search');
        $active = $request->boolean('active', TRUE);
        
        $total = 0;
        $data = NULL;
        $paginator = NULL;

        if ($slug === 'persediaan') {
            $query = MutasiTambah::query();
            $query->with(['master_persediaan']);
            $query->where('kode_pembukuan', '01');
            $query->where('kode_perolehan', '00');
            $query->where(DB::raw('YEAR(tgl_pembukuan)'), $this->_setting()->tahun_anggaran);
            $query->whereHas('master_persediaan', function ($qMaster) use ($search) {
                $qMaster->where(function ($qm) use ($search) {
                    $qm->orWhere('kode_barang', 'like', '%'.$search.'%');
                    $qm->orWhere('kode_register', 'like', '%'.$search.'%');
                    $qm->orWhere('nama_barang', 'like', '%'.$search.'%');
                    $qm->orWhere('spesifikasi', 'like', '%'.$search.'%');
                });
            });

            $total = $query->sum('nilai_perolehan');
            $paginator = $query->paginate(25);

            $collections = [];
            foreach ($paginator as $item) {
                $collection = collect($item);
                $filtered = $collection->only([
                    'id',
                    'tgl_pembukuan',
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

            $data = collect($collections)->groupBy('master_persediaan.kode_barang')->map(function ($item, $key) {
                $findDoc = KodefikasiSubSubRincianObjek::findOrFail($key);
                $rows = collect($item)->sortBy('master_persediaan.kode_register');

                return [
                    'key' => $findDoc,
                    'data' => $rows,
                ];
            })->values();
        } else {
            $kodeJenis = '1.3.1';
            switch ($slug) {
                case 'tanah': $kodeJenis = '1.3.1'; break;
                case 'peralatan-mesin': $kodeJenis = '1.3.2'; break;
                case 'gedung-bangunan': $kodeJenis = '1.3.3'; break;
                case 'jij': $kodeJenis = '1.3.4'; break;
                case 'atl': $kodeJenis = '1.3.5'; break;
                case 'kdp': $kodeJenis = '1.3.6'; break;
                case 'atb': $kodeJenis = '1.5.3'; break;
                case 'aset-lain': $kodeJenis = '1.5.4'; break;
                default: $kodeJenis = '1.3.1'; break;
            }

            $query = Barang::query();
            $query->with(['kodefikasi']);
            $query->where(DB::raw('LEFT(kode_neraca, 5)'), $kodeJenis);
            $query->where(function ($q) use ($search) {
                $q->orWhere('kode_barang', 'like', '%'.$search.'%');
                $q->orWhere('kode_register', 'like', '%'.$search.'%');
                $q->orWhere('nama_barang', 'like', '%'.$search.'%');
                $q->orWhere('spesifikasi', 'like', '%'.$search.'%');
                $q->orWhere('no_polisi', 'like', '%'.$search.'%');
                $q->orWhere('no_rangka', 'like', '%'.$search.'%');
                $q->orWhere('no_mesin', 'like', '%'.$search.'%');
            });
            $query->orderBy('kode_barang');
            $query->orderBy('kode_register');

            $total = $query->sum('nilai_perolehan');
            $paginator = $query->paginate(25);

            $collections = [];
            foreach ($paginator as $item) {
                $filtered = $item;
                array_push($collections, $filtered);
            }

            $data = collect($collections)->groupBy('kode_barang')->map(function ($item, $key) {
                $findDoc = KodefikasiSubSubRincianObjek::findOrFail($key);

                return [
                    'key' => $findDoc,
                    'data' => $item,
                ];
            })->values();
        }

        // return response()->json($data);

        return view('saldo-awal.index', [
            'slug' => $slug,
            'pageTitle' => $this->_pageTitleFromSlug($slug),
            'filter' => [
                'search' => $search,
                'active' => $active,
            ],
            'total' => $total,
            'data' => $data,
            'paginator' => $paginator,
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
        return view('saldo-awal.form', [
            'slug' => $slug,
            'pageTitle' => $this->_pageTitleFromSlug($slug),
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
        $validated['saldo_jumlah_barang'] = 0;
        $validated['saldo_harga_satuan'] = 0;
        $validated['saldo_nilai_perolehan'] = 0;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $data = new MutasiTambah($validated);
        $data->save();

        return redirect()->back()->with('success', 'Item berhasil ditambahkan.');
    }

    public function import(Request $request) 
    {
        $slug = $request->input('slug');

        $request->validate([
            'document' => ['required', 'file'],
        ]);

        if ($slug == 'persediaan') {
            Excel::import(new PersediaanImport, $request->file('document'));
        } elseif ($slug == 'tanah') {
            Excel::import(new TanahImport, $request->file('document'));
        } elseif ($slug == 'peralatan-mesin') {
            Excel::import(new PeralatanMesinImport, $request->file('document'));
        } elseif ($slug == 'gedung-bangunan') {
            Excel::import(new GedungBangunanImport, $request->file('document'));
        } elseif ($slug == 'jij') {
            Excel::import(new JIJImport, $request->file('document'));
        } elseif ($slug == 'atl') {
            Excel::import(new ATLImport, $request->file('document'));
        } elseif ($slug == 'kdp') {
            Excel::import(new KDPImport, $request->file('document'));
        } elseif ($slug == 'atb') {
            Excel::import(new ATBImport, $request->file('document'));
        }

        return redirect()->back()->with('success', 'Import data berhasil.');
    }
}
