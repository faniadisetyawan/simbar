<?php

use Illuminate\Database\Seeder;
use App\Bidang;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bidang::create(['id' => 1, 'nama' => 'Sekretariat']);
        Bidang::create(['id' => 2, 'nama' => 'Bidang Pendidikan Dasar']);
        Bidang::create(['id' => 3, 'nama' => 'Bidang Ketenagaan']);
        Bidang::create(['id' => 4, 'nama' => 'Bidang Pendidikan Anak Usia Dini, Pemuda dan Olahraga']);
        Bidang::create(['id' => 5, 'nama' => 'Bidang Sarpras, Fasilitasi dan Pengembangan']);
    }
}
