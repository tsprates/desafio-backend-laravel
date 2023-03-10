<?php

namespace Tests\Feature;

use App\Models\Endereco;
use App\Models\Paciente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PacientesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Paciente::unsetEventDispatcher();
        Endereco::unsetEventDispatcher();
    }

    public function test_estrutura_da_lista_de_pacientes()
    {
        $response = $this->get('api/pacientes');
        $response->assertSuccessful()
            ->assertJsonStructure(['current_page', 'data', 'total']);
    }

    public function test_adiciona_paciente()
    {
        Storage::fake('public/images/');

        $pacienteArray = Paciente::factory()
            ->make(['foto' => UploadedFile::fake()->image('fake.jpg', 150, 150)])
            ->toArray();
        $enderecoArray = Endereco::factory()->make()->toArray();

        $response = $this->post('api/pacientes', [...$pacienteArray, ...$enderecoArray]);
        $response->assertCreated()
            ->assertJson(['status' => true]);
    }

    public function test_atualiza_paciente()
    {
        $paciente = Paciente::factory()
            ->has(Endereco::factory()->count(1))
            ->create();

        $pacienteId = $paciente->id;
        $pacienteArray = $paciente->toArray();
        $enderecoArray = $paciente->endereco()->first()->toArray();

        unset($pacienteArray['foto']);

        $pacienteArray['nome_completo'] = 'test user';

        $response = $this->put('api/pacientes/' . $pacienteId, [...$pacienteArray, ...$enderecoArray]);
        $response->assertStatus(200)
            ->assertJsonPath('data.nome_completo', 'test user');
    }

    public function test_tenta_criar_paciente_com_cpf_invalido()
    {
        $pacienteArray = Paciente::factory()
            ->make()
            ->toArray();
        $enderecoArray = Endereco::factory()->make()->toArray();

        // CPF inválido
        $paciente['cpf'] = '111.111.111-11';

        $response = $this->post('api/pacientes', [...$pacienteArray, ...$enderecoArray]);
        $response->assertStatus(422)
            ->assertJson(['status' => false]);
    }

    public function test_tenta_criar_paciente_com_cns_invalido()
    {
        $pacienteArray = Paciente::factory()
            ->make()
            ->toArray();
        $enderecoArray = Endereco::factory()->make()->toArray();

        // CNS inválido
        $paciente['cns'] = '12345';

        $response = $this->post('api/pacientes', [...$pacienteArray, ...$enderecoArray]);
        $response->assertStatus(422)
            ->assertJson(['status' => false]);
    }
}
