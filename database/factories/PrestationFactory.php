<?php

namespace Database\Factories;

use App\Models\Prestation;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrestationFactory extends Factory
{
    protected $model = Prestation::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->word(), // Nom aléatoire
            'duree' => $this->faker->numberBetween(30, 120), // Durée entre 30 et 120 min
        ];
    }
}
