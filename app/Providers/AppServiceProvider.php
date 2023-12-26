<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Http\Models\Portal_configuracion;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        \Debugbar::disable();

        $configs = Portal_configuracion::Where('pcPortal', 'D')->get();

        foreach ($configs as $config) {
            $portConfActivo = $config->pcEstado == 'A' ? true: false;
            View::share($config->pcClave, $portConfActivo);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
