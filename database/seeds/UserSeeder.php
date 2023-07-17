<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => 1,
            'username' => 'penggunabarang',
            'nama' => 'Mokhamat Muhsin',
            'nip' => '19830208 201001 1 008',
            'password' => Hash::make('disdik'),
            'bidang_id' => 1,
            'role_id' => 1,
        ]);

        User::create([
            'id' => 2,
            'username' => 'penatausahaan',
            'nama' => 'Dina Puspitawati, SKM.',
            'nip' => '19760610 200901 2 005',
            'password' => Hash::make('disdik'),
            'bidang_id' => 1,
            'role_id' => 2,
        ]);

        User::create([
            'id' => 3,
            'username' => 'pengurusbarang',
            'nama' => 'Supriyono, S.Sos',
            'nip' => '19930103 202211 1 003',
            'password' => Hash::make('disdik'),
            'bidang_id' => 1,
            'role_id' => 3,
        ]);

        User::create([
            'id' => 4,
            'username' => 'seksismp',
            'nama' => 'Adi Akbar Jaya Prasetia',
            'password' => Hash::make('disdik'),
            'bidang_id' => 2,
            'role_id' => 4,
        ]);

        User::create([
            'id' => 5,
            'username' => 'seksiguru',
            'nama' => 'Moh. Kamim, A.Ma',
            'password' => Hash::make('disdik'),
            'bidang_id' => 3,
            'role_id' => 4,
        ]);

        User::create([
            'id' => 6,
            'username' => 'seksidikmas',
            'nama' => 'Ery Pancana Putra, A.Md',
            'nip' => '19860525 201101 1 016',
            'password' => Hash::make('disdik'),
            'bidang_id' => 4,
            'role_id' => 4,
        ]);

        User::create([
            'id' => 7,
            'username' => 'seksifasilitasi',
            'nama' => 'Yulistiyanto',
            'nip' => '19750308 201408 1 001',
            'password' => Hash::make('disdik'),
            'bidang_id' => 5,
            'role_id' => 4,
        ]);
    }
}
