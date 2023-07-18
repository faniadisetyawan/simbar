<?php

namespace App\Imports\Seeds;

use App\KodefikasiSubSubRincianObjek;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KodefikasiSubSubRincianObjekImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new KodefikasiSubSubRincianObjek([
            'kode' => $row['kode'],
            'uraian' => $row['uraian'],
            'kode_sub_rincian_objek' => $row['kode_sub_rincian_objek'],
            'nilai_kapitalisasi' => $row['nilai_kapitalisasi'],
            'masa_manfaat' => $row['masa_manfaat'],
        ]);
    }
}
