<?php

namespace App\Utilities;

class ElectricOven implements Oven
{
    /**
     * @inheritDoc
     */
    public function heatUp(): Oven
    {
        // TODO: Implement heatUp() method.
    }

    /**
     * @inheritDoc
     */
    public function bake(Pizza &$pizza): Oven
    {
        $pizza->setStatus(Pizza::STATUS_COOKED);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function turnOff(): Oven
    {
        // TODO: Implement turnOff() method.
    }
}
