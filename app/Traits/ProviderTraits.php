<?php

namespace App\Traits;

use App\Bidang;
use App\PersediaanMaster;

trait ProviderTraits
{
    public function _generateNUSP($kodeBarang) 
    {
        $maxValue = PersediaanMaster::where('kode_barang', $kodeBarang)->max('kode_register');

        return sprintf("%06s", (int)$maxValue + 1);
    }

    public function _pageTitleFromSlug($slug) 
    {
        $pageTitle = '';

        switch ($slug) {
            case 'persediaan': $pageTitle = 'Persediaan'; break;
            case 'tanah': $pageTitle = 'Tanah'; break;
            case 'peralatan-mesin': $pageTitle = 'Peralatan dan Mesin'; break;
            case 'gedung-bangunan': $pageTitle = 'Gedung dan Bangunan'; break;
            case 'jij': $pageTitle = 'Jalan, Irigasi dan Jaringan'; break;
            case 'atl': $pageTitle = 'Aset Tetap Lainnya'; break;
            case 'kdp': $pageTitle = 'Konstruksi Dalam Pengerjaan'; break;
            case 'atb': $pageTitle = 'Aset Tidak Berwujud'; break;
            case 'aset-lain': $pageTitle = 'Aset Lain-Lain'; break;
            default: $pageTitle = ''; break;
        }

        return $pageTitle;
    }

    public function _getBidang() 
    {
        return Bidang::get();
    }
}
