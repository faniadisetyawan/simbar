<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ProviderTraits;
use App\Traits\MutasiTraits;
use App\PersediaanMaster;
use App\MutasiTambah;
use App\MutasiKurang;
use App\PersediaanOpname;
use PDF;

class MutasiController extends Controller
{
    use ProviderTraits, MutasiTraits;

    private $pageTitle;

    public function __construct() {
        $this->pageTitle = 'Mutasi Persediaan';
    }

    private function _laporan($tglPembukuan) 
    {
        $groupMutasiTambah = MutasiTambah::groupBy('barang_id')->get(['barang_id'])->pluck('barang_id');
        $master = PersediaanMaster::with(['kodefikasi'])->whereIn('id', $groupMutasiTambah)->get();

        $collections = [];
        foreach ($master as $barang) {
            $getSaldoAwal = $this->_getSumSaldoAwalByDate($tglPembukuan, $barang->id);
            $getMutasiTambah = $this->_getSumMutasiTambahByDate($tglPembukuan, $barang->id);
            $getMutasiKurang = $this->_getSumMutasiKurangByDate($tglPembukuan, $barang->id);

            $logMutasi = $this->logMutasiTrait($tglPembukuan, $barang->id);
            $latest = collect($logMutasi)->last();

            $barang->saldo_awal = (object)[
                'stok' => (int)$getSaldoAwal,
            ];
            $barang->mutasi_tambah = (object)[
                'stok' => (int)$getMutasiTambah,
            ];
            $barang->mutasi_kurang = (object)[
                'stok' => (int)$getMutasiKurang,
            ];
            $barang->saldo_akhir = (object)[
                'stok' => $latest->stok,
                'nilai_perolehan' => $latest->nilai_akhir,
            ];

            array_push($collections, $barang);
        }

        $total = (object)[
            'saldo_awal' => (object)[
                'jumlah_barang' => collect($collections)->sum('saldo_awal.stok'),
            ],
            'mutasi_tambah' => (object)[
                'jumlah_barang' => collect($collections)->sum('mutasi_tambah.stok'),
            ],
            'mutasi_kurang' => (object)[
                'jumlah_barang' => collect($collections)->sum('mutasi_kurang.stok'),
            ],
            'saldo_akhir' => (object)[
                'jumlah_barang' => collect($collections)->sum('saldo_akhir.stok'),
                'nilai_perolehan' => collect($collections)->sum('saldo_akhir.nilai_perolehan'),
            ],
        ];
        $grouped = collect($collections)->groupBy('kode_barang')->map(function ($item, $key) {
            return (object)[
                'key' => $item[0]['kodefikasi'],
                'data' => $item,
            ];
        })->values();

        return Pdf::loadView('laporan.pdf.mutasi-persediaan', [
            'pageTitle' => 'Rekapitulasi Mutasi Barang Persediaan',
            'tglPembukuan' => $tglPembukuan,
            'total' => $total,
            'data' => $grouped,
        ])->setPaper('a4', 'landscape')->stream();
    }

    public function index(Request $request) 
    {
        $tglPembukuan = $request->query('tgl_pembukuan');

        if ($request->filled('tgl_pembukuan') === FALSE) {
            return view('laporan.mutasi-persediaan', [
                'pageTitle' => $this->pageTitle,
            ]);
        }

        return $this->_laporan($tglPembukuan);
    }

    public function kartuPersediaan(Request $request) 
    {
        $tglPembukuan = $request->query('tgl_pembukuan');
        $barangId = $request->query('barang_id');

        $pageTitle = 'Kartu Barang Persediaan';
        
        if (!empty($tglPembukuan) && !empty($barangId)) {
            $master = PersediaanMaster::findOrFail($barangId);
            $data = $this->logMutasiTrait($tglPembukuan, $barangId);

            return Pdf::loadView('laporan.pdf.kartu-persediaan', [
                'pageTitle' => $pageTitle,
                'tglPembukuan' => $tglPembukuan,
                'master' => $master,
                'data' => $data,
            ])->setPaper('a4', 'landscape')->stream();
        }

        return view('laporan.kartu-persediaan', [
            'pageTitle' => $pageTitle,
        ]);
    }

    public function perolehan(Request $request, $slug) 
    {
        $tglPembukuan = $request->query('tgl_pembukuan');

        $pageTitle = '';
        $kodePerolehan = '';

        if ($slug === 'pengadaan') {
            $pageTitle = 'Pengadaan';
            $kodePerolehan = '01';
        } elseif ($slug === 'hibah') {
            $pageTitle = 'Hibah';
            $kodePerolehan = '02';
        }

        if (isset($tglPembukuan)) {
            $collections = MutasiTambah::where('kode_perolehan', $kodePerolehan)
                ->whereBetween('tgl_pembukuan', [$this->_startDate(), $tglPembukuan])
                ->orderBy('tgl_pembukuan', 'ASC')
                ->get();

            $data = $collections->groupBy('slug_dokumen')->map(function ($item, $key) {
                return (object)[
                    'tgl_dokumen' => $item[0]['tgl_dokumen'],
                    'no_dokumen' => $item[0]['no_dokumen'],
                    'jenis_dokumen' => $item[0]['jenis_dokumen'],
                    'total' => $item->sum('nilai_perolehan'),
                    'data' => $item,
                ];
            })->values();
    
            // return response()->json($data);
            return Pdf::loadView('laporan.pdf.perolehan', [
                'pageTitle' => $pageTitle,
                'tglPembukuan' => $tglPembukuan,
                'totalNilaiPerolehan' => $collections->sum('nilai_perolehan'),
                'data' => $data,
            ])->setPaper('a4', 'landscape')->stream();
        }

        return view('laporan.perolehan', [
            'pageTitle' => $pageTitle,
            'slug' => $slug,
        ]);
    }

    public function reklasifikasi(Request $request)
    {
        $tglPembukuan = $request->query('tgl_pembukuan');

        $pageTitle = 'Reklasifikasi';

        if (isset($tglPembukuan)) {
            $query = MutasiKurang::query();
            $query->with(['master_persediaan.kodefikasi']);
            $query->where('kode_pembukuan', '06');
            $query->whereBetween('tgl_pembukuan', [$this->_startDate(), $tglPembukuan]);
            $query->orderBy('tgl_pembukuan', 'DESC');
            $collections = $query->get();

            $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
                $findDoc = MutasiKurang::where('slug_dokumen', $key)->first();

                return (object)[
                    'kode_pembukuan' => $findDoc['kode_pembukuan'],
                    'kode_perolehan' => $findDoc['kode_perolehan'],
                    'kode_jenis_dokumen' => $findDoc['kode_jenis_dokumen'],
                    'jenis_dokumen' => $findDoc['jenis_dokumen'],
                    'no_dokumen' => $findDoc['no_dokumen'],
                    'slug_dokumen' => $findDoc['slug_dokumen'],
                    'tgl_dokumen' => $findDoc['tgl_dokumen'],
                    'uraian_dokumen' => $findDoc['uraian_dokumen'],
                    'bidang' => $findDoc['bidang'],
                    'total' => collect($item)->sum('nilai_perolehan'),
                    'data' => $item,
                ];
            })->values();

            // return response()->json($grouped);

            return Pdf::loadView('laporan.pdf.reklasifikasi', [
                'pageTitle' => $pageTitle,
                'tglPembukuan' => $tglPembukuan,
                'totalNilaiPerolehan' => $collections->sum('nilai_perolehan'),
                'data' => $grouped,
            ])->setPaper('a4', 'landscape')->stream();
        }

        return view('laporan.reklasifikasi', [
            'pageTitle' => $pageTitle,
        ]);
    }

    public function penghapusan(Request $request) 
    {
        $tglPembukuan = $request->query('tgl_pembukuan');

        $pageTitle = 'Penghapusan';

        if (isset($tglPembukuan)) {
            $query = MutasiKurang::query();
            $query->with(['master_persediaan.kodefikasi']);
            $query->where('kode_pembukuan', '14');
            $query->whereBetween('tgl_pembukuan', [$this->_startDate(), $tglPembukuan]);
            $query->orderBy('tgl_pembukuan', 'DESC');
            $collections = $query->get();
    
            $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
                $findDoc = MutasiKurang::where('slug_dokumen', $key)->first();
    
                return (object)[
                    'kode_pembukuan' => $findDoc['kode_pembukuan'],
                    'kode_perolehan' => $findDoc['kode_perolehan'],
                    'kode_jenis_dokumen' => $findDoc['kode_jenis_dokumen'],
                    'jenis_dokumen' => $findDoc['jenis_dokumen'],
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

            // return response()->json($grouped);

            return Pdf::loadView('laporan.pdf.penghapusan', [
                'pageTitle' => $pageTitle,
                'tglPembukuan' => $tglPembukuan,
                'totalNilaiPerolehan' => $collections->sum('nilai_perolehan'),
                'data' => $grouped,
            ])->setPaper('a4', 'landscape')->stream();
        }

        return view('laporan.penghapusan', [
            'pageTitle' => $pageTitle,
        ]);
    }

    public function stockOpname(Request $request) 
    {
        $tglPembukuan = $request->query('tgl_pembukuan');

        $pageTitle = 'Stock Opname';

        if (isset($tglPembukuan)) {
            $query = PersediaanOpname::query();
            $query->with(['master_persediaan.kodefikasi', 'mutasi_tambah', 'mutasi_kurang']);
            $query->whereBetween('tgl_pembukuan', [$this->_startDate(), $tglPembukuan]);
            $query->orderBy('tgl_pembukuan', 'DESC');
            $collections = $query->get();

            $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
                $findDoc = PersediaanOpname::where('slug_dokumen', $key)->first();

                $total = 0;
                foreach ($item as $row) {
                    $total += collect($row['mutasi_tambah'])->sum('nilai_perolehan');
                    $total += collect($row['mutasi_kurang'])->sum('nilai_perolehan');
                }

                return (object)[
                    'kode_pembukuan' => $findDoc['kode_pembukuan'],
                    'kode_perolehan' => $findDoc['kode_perolehan'],
                    'kode_jenis_dokumen' => $findDoc['kode_jenis_dokumen'],
                    'jenis_dokumen' => $findDoc['jenis_dokumen'],
                    'no_dokumen' => $findDoc['no_dokumen'],
                    'slug_dokumen' => $findDoc['slug_dokumen'],
                    'tgl_dokumen' => $findDoc['tgl_dokumen'],
                    'uraian_dokumen' => $findDoc['uraian_dokumen'],
                    'bidang_id' => $findDoc['bidang_id'],
                    'bidang' => $findDoc['bidang'],
                    'total' => $total,
                    'data' => $item,
                ];
            })->values();

            // return response()->json($grouped);

            return Pdf::loadView('laporan.pdf.stock-opname', [
                'pageTitle' => $pageTitle,
                'tglPembukuan' => $tglPembukuan,
                'data' => $grouped,
            ])->setPaper('a4', 'landscape')->stream();
        }

        return view('laporan.stock-opname', [
            'pageTitle' => $pageTitle,
        ]);
    }

    public function penyaluran(Request $request)
    {
        $tglPembukuan = $request->query('tgl_pembukuan');

        $pageTitle = 'Penyaluran Barang';

        if (isset($tglPembukuan)) {
            $query = MutasiKurang::query();
            $query->with(['master_persediaan.kodefikasi']);
            $query->where('kode_pembukuan', '31');
            $query->where('kode_jenis_dokumen', '10');
            $query->whereBetween('tgl_pembukuan', [$this->_startDate(), $tglPembukuan]);
            $query->orderBy('tgl_pembukuan', 'DESC');
            $collections = $query->get();

            $grouped = collect($collections)->groupBy('slug_dokumen')->map(function ($item, $key) {
                $findDoc = MutasiKurang::where('slug_dokumen', $key)->first();

                return (object)[
                    'kode_pembukuan' => $findDoc['kode_pembukuan'],
                    'kode_perolehan' => $findDoc['kode_perolehan'],
                    'kode_jenis_dokumen' => $findDoc['kode_jenis_dokumen'],
                    'jenis_dokumen' => $findDoc['jenis_dokumen'],
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

            // return response()->json($grouped);

            return Pdf::loadView('laporan.pdf.penyaluran', [
                'pageTitle' => $pageTitle,
                'tglPembukuan' => $tglPembukuan,
                'totalNilaiPerolehan' => $collections->sum('jumlah_barang'),
                'data' => $grouped,
            ])->setPaper('a4', 'landscape')->stream();
        }

        return view('laporan.penyaluran', [
            'pageTitle' => $pageTitle,
        ]);
    }
}
