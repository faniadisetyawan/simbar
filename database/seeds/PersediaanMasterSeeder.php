<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Seeds\PersediaanMasterImport;

class PersediaanMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new PersediaanMasterImport, public_path('seeds/persediaan-master.xlsx'));
    }
}
