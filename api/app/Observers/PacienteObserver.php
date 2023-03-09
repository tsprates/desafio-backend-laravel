<?php

namespace App\Observers;

use App\Models\Paciente;
use Elastic\Elasticsearch\ClientBuilder;

class PacienteObserver
{
    private static function elasticsearchClient()
    {
        return ClientBuilder::create()
            ->setHosts([env('ELASTICSEARCH_HOST')])
            ->setBasicAuthentication(env('ELASTICSEARCH_USER', 'elastic'), env('ELASTICSEARCH_PASSWORD', 'changeme'))
            ->build();
    }

    public function created(Paciente $paciente): void
    {
        self::elasticsearchClient()->index([
            'index' => $paciente->getTable(),
            'type' => '_doc',
            'id' => $paciente->id,
            'body' => $paciente->toJson(),
        ]);
    }

    public function updated(Paciente $paciente): void
    {
        self::elasticsearchClient()->update([
            'index' => $paciente->getTable(),
            'type' => '_doc',
            'id' => $paciente->id,
            'body' => $paciente->toJson(),
        ]);
    }

    public function deleted(Paciente $paciente): void
    {
        self::elasticsearchClient()->index([
            'index' => $paciente->getTable(),
            'id' => $paciente->id,
        ]);
    }
}
