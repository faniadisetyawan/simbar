<?php

namespace App\Imports\Seeds;

use App\KodefikasiObjek;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KodefikasiObjekImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new KodefikasiObjek([
            'kode' => $row['kode'],
            'uraian' => $row['uraian'],
            'kode_jenis' => $row['kode_jenis'],
        ]);
    }
}
