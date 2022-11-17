<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

class ContinentFactory extends Factory
{

    protected $continents = [
        'AF',
        'AN',
        'AS',
        'EU',
        'NA',
        'OC',
        'SA'
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'code' => 'EU'
        ];
    }
}
