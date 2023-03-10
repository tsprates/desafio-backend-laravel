<?php

namespace App\Observers;

use App\Models\Paciente;
use Elastic\Elasticsearch\Client;

class PacienteObserver
{
    private $elasticsearchClient;

    public function __construct(Client $elasticsearchClient)
    {
        $this->elasticsearchClient = $elasticsearchClient;
    }

    public function created(Paciente $paciente): void
    {
        $this->elasticsearchClient->index([
            'index' => $paciente->getTable(),
            'type' => env('ELASTICSEARCH_TYPE'),
            'id' => $paciente->id,
            'body' => $paciente->toJson(),
        ]);
    }

    public function updated(Paciente $paciente): void
    {
        $this->elasticsearchClient->update([
            'index' => $paciente->getTable(),
            'type' => env('ELASTICSEARCH_TYPE'),
            'id' => $paciente->id,
            'body' => $paciente->toJson(),
        ]);
    }

    public function deleted(Paciente $paciente): void
    {
        $this->elasticsearchClient->index([
            'index' => $paciente->getTable(),
            'id' => $paciente->id,
        ]);
    }
}
