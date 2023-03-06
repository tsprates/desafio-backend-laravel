<?php

namespace Database\Seeders;

use App\Models\Endereco;
use App\Models\Paciente;
use Database\Factories\PacienteFactory;
use Illuminate\Database\Seeder;

class PacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Paciente::factory()
            ->count(5)
            ->has(Endereco::factory()->count(1))
            ->create();
    }
}
