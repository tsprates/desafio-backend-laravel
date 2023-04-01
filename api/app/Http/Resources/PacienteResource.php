<?php

namespace App\Http\Resources;

use App\Models\Paciente;
use Illuminate\Http\Resources\Json\JsonResource;

class PacienteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'nome_completo' => $this->nome_completo,
            'nome_completo_mae' => $this->nome_completo_mae,
            'cpf' => $this->cpf,
            'cns' => $this->cns,
            'data_nascimento' => $this->data_nascimento,
            'cep' => $this->endereco->cep,
            'endereco' => $this->endereco->endereco,
            'numero' => $this->endereco->numero,
            'bairro' => $this->endereco->bairro,
            'complemento' => $this->endereco->complemento,
            'cidade' => $this->endereco->cidade,
            'estado' => $this->endereco->estado,
        ];
    }
}
