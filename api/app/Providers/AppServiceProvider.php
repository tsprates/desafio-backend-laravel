<?php

namespace App\Providers;

use App\Models\Paciente;
use App\Observers\PacienteObserver;
use Illuminate\Support\ServiceProvider;

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

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paciente::observe(PacienteObserver::class);
    }
}
