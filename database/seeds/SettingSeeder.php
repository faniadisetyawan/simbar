<?php

use Illuminate\Database\Seeder;

use App\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'app_name' => 'SIMBAR',
            'app_description' => 'Sistem Informasi Manajemen Barang',
            'app_company' => 'Dinas Pendidikan Kabupaten Kediri',
            'app_version' => '2.0',
            'tahun_anggaran' => 2023,
        ]);
    }
}
