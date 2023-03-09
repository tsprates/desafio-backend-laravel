<?php

namespace App\Http\Controllers;

use App\Http\Requests\PacienteRequest;
use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use App\Jobs\ImportaPacientesCsv;
use App\Models\Paciente;
use Elastic\Elasticsearch\Client;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
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
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->all();
        if ($request->file('foto')) {
            $data['foto'] = $request->file('foto')->store('public/images/');
        }

        $paciente = Paciente::create($data);
        $endereco = $paciente->endereco()->create($data);

        return response()->json([
            'status' => true,
            'data' => [...$paciente->toArray(), ...$endereco->toArray()],
        ], Response::HTTP_CREATED);
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

        $data = $request->all();
        if ($request->file('foto')) {
            Storage::delete($paciente->foto);
            $data['foto'] = $request->file('foto')->store('public/images/');
        }

        $paciente->update($data);
        $paciente->endereco->update($data);

        return response()->json([
            'status' => true,
            'data' => [...$paciente->toArray(), ...$paciente->endereco->toArray()],
        ]);
    }

    public function destroy(Paciente $paciente)
    {
        if ($paciente->endereco()->delete() && $paciente->delete()) {
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Importa o arquivo CSV de pacientes.
     */
    public function importCsv(Request $request)
    {
        if (!$request->file('arquivo')) {
            return response()
                ->json(['status' => false, 'data' => 'csv não encontrado'], 400);
        }

        $path = Storage::put('public/csv', $request->file('arquivo'));

        dispatch(new ImportaPacientesCsv(basename($path)));

        return response()->json(['status' => true]);
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
