<?php

namespace App\Utilities;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Recipe;
use Illuminate\Support\Collection;

class Luigis
{
    /** @var Fridge */
    private $fridge;
    /** @var Oven */
    private $oven;

    public function __construct(Oven $oven = null)
    {
        $this->fridge = new Fridge();
        $this->oven = $oven ? $oven : new ElectricOven();
    }

    public function restockFridge(): void
    {
        /** @var Ingredient $ingredient */
        foreach (Ingredient::all() as $ingredient) {
            $this->fridge->add($ingredient, 10);
        }
    }

    // todo create this function (returns a collection of cooked pizzas)
    /**
     * @param Order $order
     * @return Pizza[]|Collection
     */
    public function deliver(Order $order): Collection
    {
        // prepare and cook each recipe in the order
    }

    // todo create this function (returns a raw pizza)
    // note:
    //  you can only create a new Pizza if you first take all the
    //  ingredients required by the recipe from the fridge
    private function prepare(Recipe $recipe): Pizza
    {
        // 1) Check fridge has enough of each ingredient
        // 2) restockFridge if needed
        // 3) take ingredients from the fridge
        // 4) create new Pizza
    }

    // todo create this function (use the oven to bake the pizza)
    private function cook(Pizza &$pizza): void
    {

    }
}
