<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Continent>
 */
class ContinentFactory extends Factory
{

    protected $continents;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return ['code' => 'EU'];
//        $this->continents = Http::get('http://country.io/continent.json')->json();
    }
}
