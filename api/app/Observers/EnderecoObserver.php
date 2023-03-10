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

    public function saved(Endereco $endereco): void
    {
        $this->elasticsearchClient->index([
            'index' => $endereco->paciente->getTable(),
            'type' => env('ELASTICSEARCH_TYPE'),
            'id' => $endereco->paciente_id,
            'body' => json_encode([...$endereco->paciente()->get()->toArray(), ...$endereco->toArray()]),
        ]);
    }
}
