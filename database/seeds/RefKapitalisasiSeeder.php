<?php

use Illuminate\Database\Seeder;
use App\RefKapitalisasi;

class RefKapitalisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefKapitalisasi::create(['kode' => '01', 'nama' => 'Intrakomptabel']);
        RefKapitalisasi::create(['kode' => '02', 'nama' => 'Ekstrakomptabel']);
    }
}
