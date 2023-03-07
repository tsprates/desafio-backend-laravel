<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePacienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'foto' => 'file|dimensions:min_width=100,min_height=100,max_width=250,max_height=250|mimes:jpg,png',
            'nome_completo' => 'required|min:3',
            'nome_completo_mae' => 'required|min:3',
            'data_nascimento' => 'required|date',
            'cpf' => 'required|cpf|unique:pacientes,cpf',
            'cns' => 'required|cns|unique:pacientes,cns',
            'cep' => 'required|formato_cep',
            'endereco' => 'required|min:3',
            'cidade' => 'required',
            'estado' => 'required|uf',
        ];
    }
}
