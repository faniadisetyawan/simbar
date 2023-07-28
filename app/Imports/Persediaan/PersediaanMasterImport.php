<?php

namespace App\Imports\Persediaan;

use App\PersediaanMaster;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Traits\ProviderTraits;

class PersediaanMasterImport implements ToModel, WithHeadingRow
{
    use ProviderTraits;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $kodeRegister = $this->_generateNUSP($row['kode_barang']);

        return new PersediaanMaster([
            'kode_barang' => $row['kode_barang'],
            'kode_register' => $kodeRegister,
            'nama_barang' => $row['nama_barang'],
            'spesifikasi' => $row['spesifikasi'],
            'satuan' => $row['satuan'],
            'user_id' => auth()->id(),
        ]);
    }
}
