<?php

use Illuminate\Database\Seeder;
use App\RefPerolehan;

class RefPerolehanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefPerolehan::create(['kode' => '00', 'nama' => 'Saldo awal']);
        RefPerolehan::create(['kode' => '01', 'nama' => 'Pengadaan']);
        RefPerolehan::create(['kode' => '02', 'nama' => 'Hibah']);
        RefPerolehan::create(['kode' => '03', 'nama' => 'Pelaksanaan dari perjanjian/kontrak']);
        RefPerolehan::create(['kode' => '04', 'nama' => 'Ketentuan peraturan perundang-undangan']);
        RefPerolehan::create(['kode' => '05', 'nama' => 'Putusan pengadilan']);
        RefPerolehan::create(['kode' => '06', 'nama' => 'Divestasi']);
        RefPerolehan::create(['kode' => '07', 'nama' => 'Hasil Inventarisasi']);
        RefPerolehan::create(['kode' => '08', 'nama' => 'Hasil tukar-menukar']);
        RefPerolehan::create(['kode' => '09', 'nama' => 'Pembatalan penghapusan']);
        RefPerolehan::create(['kode' => '10', 'nama' => 'Perolehan/penerimaan lainnya']);
    }
}
