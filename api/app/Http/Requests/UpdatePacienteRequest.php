<?php

namespace App\Http\Requests;

use App\Models\Paciente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePacienteRequest extends FormRequest
{
    private $paciente;

    public function __construct(Paciente $paciente)
    {
        $this->paciente = $paciente;
    }

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
            'cpf' => ['required', 'cpf', Rule::unique('pacientes')->ignore($this->paciente)],
            'cns' => ['required', 'cns', Rule::unique('pacientes')->ignore($this->paciente)],
            'cep' => 'required|formato_cep',
            'endereco' => 'required|min:3',
            'cidade' => 'required',
            'estado' => 'required|uf',
        ];
    }
}
