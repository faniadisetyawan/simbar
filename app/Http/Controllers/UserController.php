<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = array(
            [
                'id' => 1,
                'username' => 'penggunabarang',
                'nama' => 'Mokhamat Muhsin',
                'nip' => '19830208 201001 1 008',
                'jabatan' => [
                    'nama' => 'Pengguna Barang',
                ],
                'bidang' => 'Sekretariat',
                'foto' => 'avatar-1.jpg',
            ],
            [
                'id' => 2,
                'username' => 'penatausahaan',
                'nama' => 'DINA PUSPITAWATI, SKM.',
                'nip' => '19760610 200901 2 005',
                'jabatan' => [
                    'nama' => 'Pejabat Penatausahaan Barang',
                ],
                'bidang' => 'Sekretariat',
                'foto' => 'avatar-2.jpg',
            ],
            [
                'id' => 3,
                'username' => 'pengurusbarang',
                'nama' => 'SUPRIYONO, S.Sos',
                'nip' => '19930103 202211 1 003',
                'jabatan' => [
                    'nama' => 'Pengurus Barang',
                ],
                'bidang' => 'Sekretariat',
                'foto' => 'avatar-3.jpg',
            ],
            [
                'id' => 4,
                'username' => 'seksismp',
                'nama' => 'ADI AKBAR JAYA PRASETIA',
                'nip' => '19930103 202211 1 003',
                'jabatan' => [
                    'nama' => 'Pengurus Barang Pembantu',
                ],
                'bidang' => 'Bidang Pendidikan Dasar',
                'foto' => NULL,
            ],
        );

        return view('user', [
            'data' => $data,
        ]);
    }
}
