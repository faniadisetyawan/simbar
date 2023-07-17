<?php

use Illuminate\Database\Seeder;
use App\UserRole;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserRole::create(['id' => 1, 'nama' => 'Pengguna Barang']);
        UserRole::create(['id' => 2, 'nama' => 'Pejabat Penatausahaan Barang']);
        UserRole::create(['id' => 3, 'nama' => 'Pengurus Barang']);
        UserRole::create(['id' => 4, 'nama' => 'Pengurus Barang Pembantu']);
    }
}
