<?php

namespace App\Observers;

use App\Models\Endereco;
use Elastic\Elasticsearch\Client;

class EnderecoObserver
{
    private $elasticsearchClient;

    public function __construct(Client $elasticsearchClient)
    {
        $this->elasticsearchClient = $elasticsearchClient;
    }

    public function created(Endereco $endereco)
    {
        $this->elasticsearchClient->index([
            'index' => $endereco->paciente->getTable(),
            'type' => env('ELASTICSEARCH_TYPE'),
            'id' => $endereco->paciente_id,
            'body' => json_encode([...$endereco->paciente->toArray(), ...$endereco->first()->toArray()]),
        ]);
    }

    public function updated(Endereco $endereco)
    {
        $this->elasticsearchClient->index([
            'index' => $endereco->paciente->getTable(),
            'type' => env('ELASTICSEARCH_TYPE'),
            'id' => $endereco->paciente_id,
            'body' => json_encode([...$endereco->paciente->toArray(), ...$endereco->first()->toArray()]),
        ]);
    }
}
