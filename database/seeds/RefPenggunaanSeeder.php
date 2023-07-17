<?php

use Illuminate\Database\Seeder;
use App\RefPenggunaan;

class RefPenggunaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefPenggunaan::create(['kode' => '01', 'nama' => 'Pengalihan atau penyerahan BMD']);
        RefPenggunaan::create(['kode' => '02', 'nama' => 'Penggunaan sementara BMD']);
        RefPenggunaan::create(['kode' => '03', 'nama' => 'Dioperasikan pihak lain']);
    }
}
