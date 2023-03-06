<?php

namespace App\Http\Controllers;

use App\Http\Requests\PacienteRequest;
use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use App\Models\Endereco;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PacientesController extends Controller
{
    public function index()
    {
        return Paciente::with('endereco')->paginate(10);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), (new StorePacienteRequest())->rules());
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $paciente = new Paciente();
        $paciente->nome_completo = $request->input('nome_completo');
        $paciente->nome_completo_mae = $request->input('nome_completo_mae');
        $paciente->data_nascimento = $request->input('data_nascimento');
        $paciente->cpf = $request->input('cpf');
        $paciente->cns = $request->input('cns');
        $paciente->save();

        $endereco = new Endereco();
        $endereco->cep = $request->input('cep');
        $endereco->endereco = $request->input('endereco');
        $endereco->numero = $request->input('numero');
        $endereco->bairro = $request->input('bairro');
        $endereco->complemento = $request->input('complemento');
        $endereco->cidade = $request->input('cidade');
        $endereco->estado = $request->input('estado');
        $endereco->paciente_id = $paciente->id;
        $endereco->save();

        return response()->json(['status' => true], Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        return Paciente::with('endereco')->find($id);
    }

    public function update(Request $request, Paciente $paciente)
    {
        $validator = Validator::make($request->all(), (new UpdatePacienteRequest($paciente))->rules());
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $paciente->nome_completo = $request->input('nome_completo');
        $paciente->nome_completo_mae = $request->input('nome_completo_mae');
        $paciente->data_nascimento = $request->input('data_nascimento');
        $paciente->cpf = $request->input('cpf');
        $paciente->cns = $request->input('cns');
        $paciente->save();

        $endereco = $paciente->endereco()->first();
        $endereco->cep = $request->input('cep');
        $endereco->endereco = $request->input('endereco');
        $endereco->numero = $request->input('numero');
        $endereco->bairro = $request->input('bairro');
        $endereco->complemento = $request->input('complemento');
        $endereco->cidade = $request->input('cidade');
        $endereco->estado = $request->input('estado');
        $endereco->paciente_id = $paciente->id;
        $endereco->save();

        return response()->json(['status' => true]);
    }

    public function destroy(Paciente $paciente)
    {
        if ($paciente->endereco()->delete() && $paciente->delete()) {
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false], Response::HTTP_BAD_REQUEST);
    }
}
