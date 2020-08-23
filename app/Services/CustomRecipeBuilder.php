<?php

namespace App\Services;

use App\Models\Recipe;

class CustomRecipeBuilder
{
    private const BASE_PIZZA_PRICE = 5.00;

    public function build(string $customPizzaName): Recipe
    {
        return Recipe::updateOrCreate([
            'name' => $customPizzaName,
            'price' => self::BASE_PIZZA_PRICE
        ]);
    }
}
