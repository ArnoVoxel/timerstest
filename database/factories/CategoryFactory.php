<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{

    public static $category_array = [
        'analyse', 'test', 'ajout de fonction'
    ];
        
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_label' => $this->faker->jobTitle,
        ];
    }
}
