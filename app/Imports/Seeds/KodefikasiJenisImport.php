<?php

namespace App\Imports\Seeds;

use App\KodefikasiJenis;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KodefikasiJenisImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new KodefikasiJenis([
            'kode' => $row['kode'],
            'uraian' => $row['uraian'],
            'kode_kelompok' => $row['kode_kelompok'],
        ]);
    }
}
