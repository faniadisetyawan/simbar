<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Traits\ProviderTraits;
use App\Traits\MutasiTraits;
use App\Barang;
use App\KodefikasiJenis;
use App\PersediaanMaster;
use App\MutasiTambah;

class DashboardController extends Controller
{
    use ProviderTraits, MutasiTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    private function _saldoAkhirPersediaan() 
    {
        $tglPembukuan = $this->_setting()->tahun_anggaran . '-12-31';

        $groupMutasiTambah = MutasiTambah::groupBy('barang_id')->get(['barang_id'])->pluck('barang_id');
        $master = PersediaanMaster::with(['kodefikasi'])->whereIn('id', $groupMutasiTambah)->get();

        $collections = [];
        foreach ($master as $barang) {
            $logMutasi = $this->logMutasiTrait($tglPembukuan, $barang->id);
            $latest = collect($logMutasi)->last();

            $barang->saldo_akhir = (object)[
                'stok' => $latest->stok,
                'nilai_perolehan' => $latest->nilai_akhir,
            ];

            array_push($collections, $barang);
        }

        $kodeJenis = '1.1.7';
        $jenis = KodefikasiJenis::find($kodeJenis);
        $nilaiPerolehan = collect($collections)->sum('saldo_akhir.nilai_perolehan');

        return [
            'kode_jenis' => $kodeJenis,
            'nilai_perolehan' => $nilaiPerolehan,
            'jenis' => $jenis,
            'icon' => 'ri-newspaper-fill',
        ];
    }

    private function _barangGroupPerJenis() 
    {
        $kodefikasi = KodefikasiJenis::whereIn('kode', ['1.3.1','1.3.2','1.3.3','1.3.4','1.3.5','1.3.6','1.5.3','1.5.4'])->get();

        $collections = [];
        $grouped = Barang::select(
                DB::raw('LEFT(kode_neraca, 5) AS kode_jenis'),
                DB::raw('SUM(nilai_perolehan) AS nilai_perolehan')
            )
            ->where('kode_kapitalisasi', '01')
            ->where('jumlah_barang', '>', 0)
            ->groupBy(DB::raw('LEFT(kode_neraca, 5)'))
            ->get();

        foreach ($kodefikasi as $jenis) {
            $obj = (object)[];
            $obj->kode_jenis = $jenis->kode;
            $obj->nilai_perolehan = 0;

            foreach ($grouped as $group) {
                if ($group->kode_jenis == $jenis->kode) {
                    $obj->nilai_perolehan = $group->nilai_perolehan;
                }
            }

            array_push($collections, $obj);
        }

        return collect($collections)->map(function ($item, $key) {
            $jenis = KodefikasiJenis::find($item->kode_jenis);

            $icon = '';
            switch ($item->kode_jenis) {
                case '1.3.1': $icon = 'ri-map-2-fill'; break;
                case '1.3.2': $icon = 'ri-car-fill'; break;
                case '1.3.3': $icon = 'ri-building-2-fill'; break;
                case '1.3.4': $icon = 'ri-road-map-fill'; break;
                case '1.3.5': $icon = 'ri-book-read-fill'; break;
                case '1.3.6': $icon = 'ri-rocket-2-fill'; break;
                case '1.5.3': $icon = 'ri-android-fill'; break;
                case '1.5.4': $icon = 'ri-delete-bin-5-fill'; break;
                default: $icon = 'ri-map-2-fill'; break;
            }

            return [
                'kode_jenis' => $item->kode_jenis,
                'nilai_perolehan' => $item->nilai_perolehan,
                'jenis' => $jenis,
                'icon' => $icon,
            ];
        })->values();
    }

    public function index() 
    {
        $saldoAkhirPersediaan = $this->_saldoAkhirPersediaan();
        $rekapPerJenis = $this->_barangGroupPerJenis();

        $mergePerJenis = [];
        foreach ($rekapPerJenis as $row) {
            array_push($mergePerJenis, $row);
        }

        array_unshift($mergePerJenis, $saldoAkhirPersediaan);

        // return response()->json($mergePerJenis);

        $recentActivity = array(
            [
                'periode' => '14 Jul 2023',
                'data' => array(
                    [
                        'pembukuan' => 'Pengadaan',
                        'bidang' => 'Sekretariat',
                        'nilai' => '15.750.000',
                        'is_perolehan' => TRUE,
                    ],
                    [
                        'pembukuan' => 'Penyaluran Persediaan',
                        'bidang' => 'Sekretariat',
                        'nilai' => '3.000.000',
                        'is_penyaluran' => TRUE,
                    ]
                ),
            ],
            [
                'periode' => '13 Jul 2023',
                'data' => array(
                    [
                        'pembukuan' => 'Penghapusan',
                        'bidang' => 'Bidang Pendidikan Dasar',
                        'nilai' => '1.500.000',
                        'is_perolehan' => FALSE,
                    ],
                    [
                        'pembukuan' => 'Pengadaan',
                        'bidang' => 'Sekretariat',
                        'nilai' => '15.750.000',
                        'is_perolehan' => TRUE,
                    ],
                    [
                        'pembukuan' => 'Pengadaan',
                        'bidang' => 'Bidang Sarpras, Fasilitasi dan Pengembangan',
                        'nilai' => '3.500.000',
                        'is_perolehan' => TRUE,
                    ],
                ),
            ],
        );

        return view('dashboard', [
            'rekapPerJenis' => $mergePerJenis,
            'recentActivity' => $recentActivity,
        ]);
    }
}
