<?php

namespace App\Utilities;

use BadFunctionCallException;
use App\Models\Recipe;
use InvalidArgumentException;

class Pizza
{
    const STATUS_RAW = 'raw';
    const STATUS_COOKED = 'cooked';
    const STATUS_OVER_COOKED = 'overCooked';
    const STATUS_PARTLY_EATEN = 'partlyEaten';
    const STATUS_ALL_EATEN = 'allEaten';
    const STATUSES = [
        self::STATUS_RAW,
        self::STATUS_COOKED,
        self::STATUS_OVER_COOKED,
        self::STATUS_PARTLY_EATEN,
        self::STATUS_ALL_EATEN,
    ];

    private $slicesRemaining = 8;
    /** @var Recipe */
    private $recipe;
    private $status = '';

    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
        $this->status = self::STATUS_RAW;
    }

    // TODO: implement function. Update pizza status to be partly eaten or all eaten
    /**
     * @throws BadFunctionCallException if no slices left to eat
     * @throws BadFunctionCallException if trying to eat a raw pizza
     */
    public function eatSlice(): void
    {

    }

    public function getSlicesRemaining(): int
    {
        return $this->slicesRemaining;
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function getName(): string
    {
        return $this->recipe->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): Pizza
    {
        if (!in_array($status, self::STATUSES)) {
            throw new InvalidArgumentException("$status is not a valid status");
        }
        $this->status = $status;
        return $this;
    }
}
