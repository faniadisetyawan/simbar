<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Seeds\KodefikasiKelompokImport;
use App\Imports\Seeds\KodefikasiJenisImport;
use App\Imports\Seeds\KodefikasiObjekImport;
use App\Imports\Seeds\KodefikasiRincianObjekImport;
use App\Imports\Seeds\KodefikasiSubRincianObjekImport;
use App\Imports\Seeds\KodefikasiSubSubRincianObjekImport;

class KodefikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new KodefikasiKelompokImport, public_path('seeds/kodefikasi/2_kelompok.xlsx'));
        Excel::import(new KodefikasiJenisImport, public_path('seeds/kodefikasi/3_jenis.xlsx'));
        Excel::import(new KodefikasiObjekImport, public_path('seeds/kodefikasi/4_objek.xlsx'));
        Excel::import(new KodefikasiRincianObjekImport, public_path('seeds/kodefikasi/5_rincian_objek.xlsx'));
        Excel::import(new KodefikasiSubRincianObjekImport, public_path('seeds/kodefikasi/6_sub_rincian_objek.xlsx'));
        Excel::import(new KodefikasiSubSubRincianObjekImport, public_path('seeds/kodefikasi/7_sub_sub_rincian_objek.xlsx'));
    }
}
