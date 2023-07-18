<?php

namespace App\Imports\Seeds;

use App\KodefikasiKelompok;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KodefikasiKelompokImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new KodefikasiKelompok([
            'kode' => $row['kode'],
            'uraian' => $row['uraian'],
        ]);
    }
}
