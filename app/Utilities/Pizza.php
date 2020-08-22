<?php

namespace App\Utilities;

use BadFunctionCallException;
use App\Models\Recipe;
use InvalidArgumentException;

class Pizza
{
    public const STATUS_RAW = 'raw';
    public const STATUS_COOKED = 'cooked';
    public const STATUS_OVER_COOKED = 'overCooked';
    public const STATUS_PARTLY_EATEN = 'partlyEaten';
    public const STATUS_ALL_EATEN = 'allEaten';

    public const STATUSES = [
        self::STATUS_RAW,
        self::STATUS_COOKED,
        self::STATUS_OVER_COOKED,
        self::STATUS_PARTLY_EATEN,
        self::STATUS_ALL_EATEN,
    ];

    private const TOTAL_SLICE_IN_PIZZA = 8;

    private const PIZZA_ALL_EATEN = 0;

    /** @var int */
    private $slicesRemaining = self::TOTAL_SLICE_IN_PIZZA;

    /** @var Recipe $recipe */
    private $recipe;

    /** @var string */
    private $status;

    /**
     * @param Recipe $recipe
     */
    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
        $this->status = self::STATUS_RAW;
    }

    /**
     * @throws BadFunctionCallException
     * @return void
     */
    public function eatSlice(): void
    {
        if ($this->getStatus() === self::STATUS_ALL_EATEN) {
            throw new BadFunctionCallException('No more Slices left to eat');
        }

        if ($this->getStatus() === self::STATUS_RAW) {
            throw new BadFunctionCallException('Trying to eat a raw pizza');
        }

        $this->slicesRemaining--;

        if ($this->slicesRemaining < self::TOTAL_SLICE_IN_PIZZA) {
            $this->setStatus(self::STATUS_PARTLY_EATEN);
        }

        if ($this->slicesRemaining === self::PIZZA_ALL_EATEN) {
            $this->setStatus(self::STATUS_ALL_EATEN);
        }
    }

    /**
     * @return int
     */
    public function getSlicesRemaining(): int
    {
        return $this->slicesRemaining;
    }

    /**
     * @return Recipe
     */
    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->recipe->name;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): Pizza
    {
        if (!in_array($status, self::STATUSES)) {
            throw new InvalidArgumentException("$status is not a valid status");
        }

        $this->status = $status;
        return $this;
    }
}
