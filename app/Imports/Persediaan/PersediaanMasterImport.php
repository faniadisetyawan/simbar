<?php

namespace App\Imports\Persediaan;

use App\PersediaanMaster;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PersediaanMasterImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PersediaanMaster([
            'id' => $row['id'],
            'kode_barang' => $row['kode_barang'],
            'kode_register' => $row['kode_register'],
            'nama_barang' => $row['nama_barang'],
            'spesifikasi' => $row['spesifikasi'],
            'satuan' => $row['satuan'],
            'user_id' => $row['user_id'],
        ]);
    }
}
