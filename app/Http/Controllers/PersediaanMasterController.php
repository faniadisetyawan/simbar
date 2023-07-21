<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Persediaan\PersediaanMasterImport;
use App\PersediaanMaster;

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

        $query = PersediaanMaster::query();
        $query->when($active === FALSE, function ($q) {
            return $q->onlyTrashed();
        });

        $data = $query->paginate()->onEachSide(2);

        return view('persediaan.master', [
            'filter' => [
                'search' => $search,
                'active' => $active,
            ],
            'data' => $data,
        ]);
    }

    public function import(Request $request) 
    {
        $request->validate([
            'document' => ['required', 'file'],
        ]);

        if ($request->hasFile('document')) {
            Excel::import(new PersediaanMasterImport, $request->file('document'));
        }
        
        return redirect('/master/persediaan');
    }
}
