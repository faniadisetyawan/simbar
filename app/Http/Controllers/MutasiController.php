<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ProviderTraits;
use App\Traits\MutasiTraits;
use App\PersediaanMaster;
use App\MutasiTambah;
use App\MutasiKurang;
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
}
