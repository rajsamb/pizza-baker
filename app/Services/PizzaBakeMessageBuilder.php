<?php

namespace App\Services;

use App\Models\Recipe;
use App\Utilities\Pizza;

class PizzaBakeMessageBuilder
{
    private const TIME_PER_PIZZA = 5;

    public function build(Pizza $pizza): string
    {
        return "{$this->totalTimeToBake($pizza->getRecipe())} minutes to bake {$pizza->getName()} pizza \n";
    }

    private function totalTimeToBake(Recipe $recipe): int
    {
        return self::TIME_PER_PIZZA + count($recipe->ingredients);
    }
}
