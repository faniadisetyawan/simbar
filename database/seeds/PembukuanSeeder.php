<?php

use Illuminate\Database\Seeder;
use App\Pembukuan;

class PembukuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pembukuan::create(['kode' => '01', 'nama' => 'Perolehan/Penerimaan']);
        Pembukuan::create(['kode' => '02', 'nama' => 'Penggunaan']);
        Pembukuan::create(['kode' => '03', 'nama' => 'Penerimaan Internal Pengguna Barang']);
        Pembukuan::create(['kode' => '04', 'nama' => 'Pengeluaran Internal Pengguna Barang']);
        Pembukuan::create(['kode' => '05', 'nama' => 'Pemanfaatan']);
        Pembukuan::create(['kode' => '06', 'nama' => 'Reklasifikasi']);
        Pembukuan::create(['kode' => '07', 'nama' => 'Koreksi']);
        Pembukuan::create(['kode' => '08', 'nama' => 'Penambahan Masa Manfaat atau Kapasitas Manfaat']);
        Pembukuan::create(['kode' => '09', 'nama' => 'Penyusutan Atau Amortisasi']);
        Pembukuan::create(['kode' => '11', 'nama' => 'Pemeliharaan']);
        Pembukuan::create(['kode' => '12', 'nama' => 'KIR']);
        Pembukuan::create(['kode' => '13', 'nama' => 'Pengamanan']);
        Pembukuan::create(['kode' => '14', 'nama' => 'Penghapusan']);
        Pembukuan::create(['kode' => '31', 'nama' => 'Penyaluran Persediaan']);
        Pembukuan::create(['kode' => '32', 'nama' => 'Stock Opname Persediaan']);
        Pembukuan::create(['kode' => '99', 'nama' => 'Pembatalan Transaksi']);
    }
}
