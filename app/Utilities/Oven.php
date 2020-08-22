<?php

namespace App\Utilities;

interface Oven
{
    /**
     * Just echo time to heat up
     *
     * @return self
     */
    public function heatUp(): self;

    /**
     * Calculate and echo time to cook
     * Update Pizza status (raw -> cooked and cooked -> overcooked)
     *
     * @param Pizza $pizza
     * @return self
     */
    public function bake(Pizza &$pizza): self;

    /**
     * Just echo 'oven is off'
     *
     * @return self
     */
    public function turnOff(): self;
}
