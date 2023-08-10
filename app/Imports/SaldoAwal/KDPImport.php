<?php

namespace App\Imports\SaldoAwal;

use App\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KDPImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Barang([
            'id' => $row['nibar'],
            'kode_barang' => $row['kode_barang'],
            'kode_register' => $row['kode_register'],
            'kode_neraca' => $row['kode_neraca'],
            'nama_barang' => $row['nama_barang'],
            'spesifikasi' => $row['alamat'],
            'tahun_pengadaan' => $row['tahun_pengadaan'],
            'jumlah_barang' => $row['jumlah_barang'],
            'satuan' => $row['satuan'],
            'harga_satuan' => ($row['nilai_perolehan'] / $row['jumlah_barang']),
            'nilai_perolehan' => $row['nilai_perolehan'],
            'keterangan' => $row['keterangan'],
            'kode_kapitalisasi' => '01',
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);
    }
}
