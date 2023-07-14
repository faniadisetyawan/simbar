<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BidangController extends Controller
{
    public function index()
    {
        $data = array(
            [
                'id' => 1,
                'nama' => 'Sekretariat',
                'kabid' => 'John Doe',
                'created_at' => '2023-07-14 16:32',
            ],
            [
                'id' => 2,
                'nama' => 'Bidang Pendidikan Dasar',
                'kabid' => 'John Doe',
                'created_at' => '2023-07-14 16:32',
            ],
            [
                'id' => 3,
                'nama' => 'Bidang Ketenagaan',
                'kabid' => 'John Doe',
                'created_at' => '2023-07-14 16:32',
            ],
            [
                'id' => 4,
                'nama' => 'Bidang Pendidikan Anak Usia Dini, Pemuda dan Olahraga',
                'kabid' => 'John Doe',
                'created_at' => '2023-07-14 16:32',
            ],
            [
                'id' => 5,
                'nama' => 'Bidang Sarpras, Fasilitasi dan Pengembangan',
                'kabid' => 'John Doe',
                'created_at' => '2023-07-14 16:32',
            ],
        );

        return view('bidang', [
            'data' => $data,
        ]);
    }
}
