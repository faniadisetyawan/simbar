<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use App\Setting;
use App\Bidang;
use App\RefJenisDokumen;
use App\PersediaanMaster;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function _getMasterPersediaan() 
    {
        $barang = PersediaanMaster::get();

        $groupedBarang = collect($barang)->groupBy('kodefikasi.concat')->map(function ($item, $key) {
            return [
                'key' => $key,
                'data' => $item,
            ];
        })->values();

        return $groupedBarang;
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::defaultView('vendor.pagination');

        if (! app()->runningInConsole()) {
            view()->share('appSetting', Setting::first());
            view()->share('appBidang', Bidang::get());
            view()->share('appJenisDokumen', RefJenisDokumen::get());
            view()->share('appMasterPersediaan', $this->_getMasterPersediaan());
        }
    }
}
