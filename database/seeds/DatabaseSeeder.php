<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BidangSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PembukuanSeeder::class);
        $this->call(RefPerolehanSeeder::class);
        $this->call(RefPenggunaanSeeder::class);
        $this->call(RefKapitalisasiSeeder::class);
        $this->call(RefJenisDokumenSeeder::class);
        $this->call(KodefikasiSeeder::class);
        $this->call(PersediaanMasterSeeder::class);
        $this->call(SettingSeeder::class);
    }
}
