<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use App\Traits\MutasiTraits;
use App\Setting;
use App\Bidang;
use App\RefJenisDokumen;
use App\PersediaanMaster;
use App\MutasiTambah;
use App\UserRole;

class AppServiceProvider extends ServiceProvider
{
    use MutasiTraits;

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

    private function _getMasterPersediaanGroupMutasi() 
    {
        $groupMutasiTambah = MutasiTambah::groupBy('barang_id')->get(['barang_id'])->pluck('barang_id');
        $master = PersediaanMaster::with(['kodefikasi'])->whereIn('id', $groupMutasiTambah)->get();

        $grouped = $master->groupBy('kode_barang')->map(function ($item, $key) {
            return (object)[
                'key' => $item[0]['kodefikasi'],
                'data' => $item,
            ];
        })->values();

        return $grouped;
    }

    private function _getUserRoles() 
    {
        return UserRole::get();
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
            view()->share('appUserRoles', $this->_getUserRoles());
            view()->share('appBidang', Bidang::get());
            view()->share('appJenisDokumen', RefJenisDokumen::get());
            view()->share('appMasterPersediaan', $this->_getMasterPersediaan());
            view()->share('appMasterPersediaanGroupMutasi', $this->_getMasterPersediaanGroupMutasi());
            view()->share('appPersediaanHasStok', $this->persediaanHasStokTrait());
        }
    }
}
