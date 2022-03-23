<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ComissaoParametroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'descricao' => $this->faker->text(20),
            'comissao_percentual' => 10.00,
            'comissao_indicado_percentual' => 3.00,
            'ativo' => 1
        ];
    }
}
