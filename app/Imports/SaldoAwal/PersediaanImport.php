<?php

namespace App\Imports\SaldoAwal;

use App\MutasiTambah;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Traits\ProviderTraits;

class PersediaanImport implements ToModel, WithHeadingRow
{
    use ProviderTraits;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new MutasiTambah([
            'kode_pembukuan' => '01',
            'kode_perolehan' => '00',
            'tgl_pembukuan' => $this->_startDate(),
            'kode_jenis_dokumen' => '99',
            'barang_id' => $row['nibar'],
            'jumlah_barang' => $row['jumlah_barang'],
            'harga_satuan' => ($row['nilai_perolehan'] / $row['jumlah_barang']),
            'nilai_perolehan' => $row['nilai_perolehan'],
            'keterangan' => $row['keterangan'],
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);
    }
}
