<?php

namespace App\Providers;

use App\Models\Endereco;
use App\Models\Paciente;
use App\Observers\EnderecoObserver;
use App\Observers\PacienteObserver;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
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
        $this->app->bind(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts([env('ELASTICSEARCH_HOST', 'localhost:9200')])
                ->setBasicAuthentication(env('ELASTICSEARCH_USER', 'elastic'), env('ELASTICSEARCH_PASSWORD', 'changeme'))
                ->build();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paciente::observe(PacienteObserver::class);
        Endereco::observe(EnderecoObserver::class);
    }
}
