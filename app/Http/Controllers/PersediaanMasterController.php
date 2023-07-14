<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PersediaanMasterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $active = $request->boolean('active', TRUE);

        $data = array(
            [
                'id' => 1,
                'kode_barang' => '1.1.7.01.03.02.001',
                'kode_register' => '000001',
                'spesifikasi_nama_barang' => 'Kertas F4',
                'specs' => 'Sinar Dunia',
                'satuan' => 'Rim',
                'kodefikasi_sub_sub_rincian_objek' => [
                    'uraian' => 'Kertas HVS',
                ],
            ],
            [
                'id' => 2,
                'kode_barang' => '1.1.7.01.03.01.001',
                'kode_register' => '000002',
                'spesifikasi_nama_barang' => 'Ballpoint',
                'specs' => 'Weiyada',
                'satuan' => 'Buah',
                'kodefikasi_sub_sub_rincian_objek' => [
                    'uraian' => 'Alat Tulis',
                ],
            ],
            [
                'id' => 3,
                'kode_barang' => '1.1.7.01.03.06.004',
                'kode_register' => '000001',
                'spesifikasi_nama_barang' => 'Tinta Printer Hitam',
                'specs' => NULL,
                'satuan' => 'Buah',
                'kodefikasi_sub_sub_rincian_objek' => [
                    'uraian' => 'Tinta/Toner Printer',
                ],
            ],
        );

        return view('persediaan.master', [
            'filter' => [
                'search' => $search,
                'active' => $active,
            ],
            'data' => [
                'total' => count($data),
                'data' => $data,
            ],
        ]);
    }
}
