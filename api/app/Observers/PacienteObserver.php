<?php

namespace App\Observers;

use App\Models\Paciente;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Facades\Log;

class PacienteObserver
{
    private $elasticsearchClient;

    public function __construct(Client $elasticsearchClient)
    {
        $this->elasticsearchClient = $elasticsearchClient;
    }

    public function updated(Paciente $paciente)
    {

        $this->elasticsearchClient->update([
            'index' => $paciente->getTable(),
            'type' => env('ELASTICSEARCH_TYPE'),
            'id' => $paciente->id,
            'body' => json_encode([...$paciente->toArray(), ...$paciente->endereco->toArray()]),
        ]);
    }

    public function deleted(Paciente $paciente)
    {
        $this->elasticsearchClient->delete([
            'index' => $paciente->getTable(),
            'id' => $paciente->id,
        ]);
    }
}
