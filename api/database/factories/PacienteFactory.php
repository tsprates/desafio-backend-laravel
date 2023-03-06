<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'foto' => $this->faker->imageUrl(150, 150, 'people'),
            'nome_completo' => $this->faker->name(),
            'nome_completo_mae' => $this->faker->name('female'),
            'data_nascimento' => $this->faker->date(),
            'cpf' => $this->faker->cpf(),
            'cns' => $this->generateFakeCns(),
        ];
    }

    private function generateFakeCns()
    {
        $numeros = $this->faker->regexify('[0-9]{11}');
        $numeros_array = array_map('intval', str_split($numeros));

        $valor = 15;
        $soma = 0;
        foreach ($numeros_array as $numero) {
            $soma += $numero * $valor;
            $valor--;
        }

        $resto = $soma % 11;
        $dv = 11 - $resto;

        if ($dv === 11) {
            $dv = 0;
        }

        if ($dv === 10) {
            $resto = ($soma + 2) % 11;
            $dv = 11 - $resto;
            return $numeros . '001' . $dv;
        }
        return $numeros . '000' . $dv;
    }
}
