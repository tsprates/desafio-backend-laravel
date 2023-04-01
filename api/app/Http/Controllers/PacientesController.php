<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use App\Http\Resources\PacienteResource;
use App\Jobs\ImportaPacientesCsv;
use App\Models\Paciente;
use Elastic\Elasticsearch\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->all();
        if ($request->file('foto')) {
            $data['foto'] = $request->file('foto')->store('public/images/');
        }

        $paciente = Paciente::create($data);
        $paciente->endereco()->create($data);

        return response()->json(new PacienteResource($paciente), Response::HTTP_CREATED);
    }

    public function show(Paciente $paciente)
    {
        return new PacienteResource($paciente);
    }

    public function update(Request $request, Paciente $paciente)
    {
        $validator = Validator::make($request->all(), (new UpdatePacienteRequest($paciente))->rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->all();
        if ($request->file('foto')) {
            Storage::delete($paciente->foto);
            $data['foto'] = $request->file('foto')->store('public/images/');
        }

        $paciente->update($data);
        $paciente->endereco()->update($request->only(
            'endereco',
            'numero',
            'bairro',
            'complemento',
            'cidade',
            'estado'
        ));

        return response()->json(new PacienteResource($paciente));
    }

    public function destroy(Paciente $paciente)
    {
        if ($paciente->endereco()->delete() && $paciente->delete()) {
            return response()->json(new PacienteResource($paciente));
        }

        return response()->json(
            new PacienteResource($paciente),
            Response::HTTP_BAD_REQUEST
        );
    }

    public function getByCpf(Request $request)
    {
        $cpf = $request->get('cpf');

        if (preg_match('/^[0-9]{11}$/', $cpf)) {
            $cpf = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})/', '\1.\2.\3-\4', $cpf);
        }

        return Paciente::with('endereco')
            ->where('cpf', $cpf)
            ->get();
    }

    public function importCsv(Request $request)
    {
        if (!$request->file('arquivo')) {
            return response()
                ->json(['error' => 'CSV não encontrado'], 400);
        }

        $path = Storage::put('public/csv', $request->file('arquivo'));

        ImportaPacientesCsv::dispatch(basename($path));

        return response(['messagem' => 'processando']);
    }

    public function search(Client $elasticsearchClient, string $query)
    {
        $result = $elasticsearchClient->search([
            'index' => 'pacientes',
            'type' => '_doc',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['nome_completo', 'nome_completo_mae', 'cpf', 'cns'],
                        'query' => $query,
                    ],
                ],
            ],
        ])->asArray();

        if ($result['hits']['total']['value'] === 0) {
            return [];
        }
        return array_column($result['hits']['hits'], '_source');
    }
}
