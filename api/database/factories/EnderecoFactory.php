<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EnderecoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cep' => $this->faker->postcode(),
            'endereco' => $this->faker->streetAddress(),
            'numero' => $this->faker->buildingNumber(),
            'bairro' => '',
            'complemento' => $this->faker->secondaryAddress(),
            'cidade' => $this->faker->city(),
            'estado' => $this->faker->stateAbbr(),
        ];
    }
}
