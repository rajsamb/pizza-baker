<?php

namespace App\Utilities;

use App\Models\FridgeContent;
use App\Models\Ingredient;
use InvalidArgumentException;

class Fridge
{
    /** @var array */
    private $stock = [];

    public function __construct()
    {
        /** @var FridgeContent $fridgeContent */
        foreach (FridgeContent::with('ingredient')->get() as $fridgeContent) {
            $this->add($fridgeContent->ingredient, $fridgeContent->amount);
        }
    }

    /**
     * @param Ingredient $ingredient
     * @param int $amount
     * @return $this
     */
    public function add(Ingredient $ingredient, int $amount): self
    {
        $class = get_class($ingredient);
        if (!isset($this->stock[$class])) {
            $this->stock[$class] = 0;
        }

        $this->updateStock($ingredient, $amount);

        return $this;
    }

    /**
     * @param Ingredient $ingredient
     * @param int $amount
     * @return $this
     */
    public function take(Ingredient $ingredient, int $amount): self
    {
        $existingAmount = $this->amount($ingredient);

        if ($existingAmount < $amount) {
            throw new InvalidArgumentException("Fridge has {$existingAmount} of {$ingredient->name}, you asked for {$amount}");
        }

        $this->updateStock($ingredient, $amount * -1);

        return $this;
    }

    /**
     * @param Ingredient $ingredient
     * @param int $amount
     */
    private function updateStock(Ingredient $ingredient, int $amount): void
    {
        $class = get_class($ingredient);

        $this->stock[$class] += $amount;

        $ingredient->stock()->updateOrCreate(
            ['ingredient_id' => $ingredient->id],
            ['amount' => $this->stock[$class]]
        );

        $ingredient->refresh();
    }

    // Check this fridge stock, not ingredient relation. See updateStock on how the stock works
    public function amount(Ingredient $ingredient): int
    {
        return $this->stock[get_class($ingredient)] ?? 0;
    }

    /**
     * @param Ingredient $ingredient
     * @param int $amount
     * @return bool
     */
    public function has(Ingredient $ingredient, int $amount): bool
    {
        $class = get_class($ingredient);

        return isset($this->stock[get_class($ingredient)]) && $this->stock[$class] > $amount;
    }
}
