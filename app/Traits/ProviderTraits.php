<?php

namespace App\Traits;

use App\PersediaanMaster;

trait ProviderTraits
{
    public function _generateNUSP($kodeBarang) 
    {
        $maxValue = PersediaanMaster::where('kode_barang', $kodeBarang)->max('kode_register');

        return sprintf("%06s", (int)$maxValue + 1);
    }
}
