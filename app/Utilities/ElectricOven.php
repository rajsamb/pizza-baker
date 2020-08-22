<?php

namespace App\Utilities;

use App\Services\PizzaBakeMessageBuilder;

class ElectricOven implements Oven
{
    /** @var PizzaBakeMessageBuilder */
    private $pizzaBakeMessageBuilder;

    /**
     * @param PizzaBakeMessageBuilder $pizzaBakeMessageBuilder
     */
    public function __construct(PizzaBakeMessageBuilder $pizzaBakeMessageBuilder)
    {
        $this->pizzaBakeMessageBuilder = $pizzaBakeMessageBuilder;
    }

    /**
     * @inheritDoc
     */
    public function heatUp(): Oven
    {
        echo "It takes 10 minutes to heat up \n.";

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bake(Pizza $pizza): Oven
    {
        echo $this->pizzaBakeMessageBuilder->build($pizza);

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
