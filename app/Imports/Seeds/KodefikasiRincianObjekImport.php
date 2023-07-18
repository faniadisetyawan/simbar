<?php

namespace App\Imports\Seeds;

use App\KodefikasiRincianObjek;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KodefikasiRincianObjekImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new KodefikasiRincianObjek([
            'kode' => $row['kode'],
            'uraian' => $row['uraian'],
            'kode_objek' => $row['kode_objek'],
            'nilai_kapitalisasi' => $row['nilai_kapitalisasi'],
            'masa_manfaat' => $row['masa_manfaat'],
        ]);
    }
}
