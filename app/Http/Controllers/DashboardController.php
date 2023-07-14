<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() 
    {
        $rekapPerJenis = array(
            [
                'title' => 'Persediaan',
                'value' => 0,
                'icon' => 'ri-newspaper-fill',
            ],
            [
                'title' => 'Tanah',
                'value' => 6319753761.58,
                'icon' => 'ri-map-2-fill',
            ],
            [
                'title' => 'Peralatan dan Mesin',
                'value' => 17404938070.33,
                'icon' => 'ri-car-fill',
            ],
            [
                'title' => 'Gedung dan Bangunan',
                'value' => 9668949720.15,
                'icon' => 'ri-building-2-fill',
            ],
            [
                'title' => 'Jalan, Irigasi dan Jaringan',
                'value' => 17611419.10,
                'icon' => 'ri-road-map-fill',
            ],
            [
                'title' => 'Aset Tetap Lainnya',
                'value' => 5070291766,
                'icon' => 'ri-book-read-fill',
            ],
            [
                'title' => 'Konstruksi Dalam Pengerjaan',
                'value' => 0,
                'icon' => 'ri-rocket-2-fill',
            ],
            [
                'title' => 'Aset Tidak Berwujud',
                'value' => 294150000,
                'icon' => 'ri-android-fill',
            ],
            [
                'title' => 'Aset Lain-Lain',
                'value' => 136330000,
                'icon' => 'ri-delete-bin-5-fill',
            ],
        );

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

        // return response()->json($recentActivity);

        return view('dashboard', [
            'rekapPerJenis' => $rekapPerJenis,
            'recentActivity' => $recentActivity,
        ]);
    }
}
