<?php

use Illuminate\Database\Seeder;
use App\RefJenisDokumen;

class RefJenisDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefJenisDokumen::create(['kode' => '01', 'nama' => 'Nota Pembelian/Pembayaran']);
        RefJenisDokumen::create(['kode' => '02', 'nama' => 'Kuitansi']);
        RefJenisDokumen::create(['kode' => '03', 'nama' => 'Surat Perintah Kerja (SPK)']);
        RefJenisDokumen::create(['kode' => '04', 'nama' => 'Surat Perjanjian']);
        RefJenisDokumen::create(['kode' => '05', 'nama' => 'Surat Pesanan']);
        RefJenisDokumen::create(['kode' => '06', 'nama' => 'Berita Acara Serah Terima (BAST)']);
        RefJenisDokumen::create(['kode' => '07', 'nama' => 'SK Kepala Daerah']);
        RefJenisDokumen::create(['kode' => '08', 'nama' => 'Nota Permintaan Barang']);
        RefJenisDokumen::create(['kode' => '09', 'nama' => 'Surat Permintaan Barang (SPB)']);
        RefJenisDokumen::create(['kode' => '10', 'nama' => 'Surat Perintah Penyaluran Barang (SPPB)']);
        RefJenisDokumen::create(['kode' => '11', 'nama' => 'Berita Acara Perubahan Fisik']);
        RefJenisDokumen::create(['kode' => '99', 'nama' => 'Dokumen Sumber Lainnya']);
    }
}
