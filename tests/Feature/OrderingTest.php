<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderRecipe;
use App\Models\Recipe;
use App\Utilities\Luigis;
use App\Utilities\Pizza;
use BadFunctionCallException;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OrderingTest extends TestCase
{
    /** @var Luigis */
    private $luigis;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        parent::setUp();
        $this->luigis = new Luigis();
    }

    public function testMargherita(): void
    {
        DB::connection(env('DB_CONNECTION'))->beginTransaction();

        try {
            // 1) Create the order
            $order = Order::create(['status' => Order::STATUS_PENDING]);
            OrderRecipe::create([
                'order_id' => $order->id,
                'recipe_id' => Recipe::MARGHERITA_ID,
            ]);

            $this->assertCount(1, $order->recipes);
            $this->assertEquals(Recipe::MARGHERITA_ID, $order->recipes->first()->id);
            $this->assertEquals(6.99, $order->getPriceAttribute());


            // 2) Deliver the order
            $pizzas = $this->luigis->deliver($order);

            $this->assertCount(1, $pizzas);


            // 3) Verify the order
            /** @var Pizza $pizza */
            $pizza = $pizzas->first();
            $this->assertEquals('Margherita', $pizza->getName());
            $this->assertEquals(Pizza::STATUS_COOKED, $pizza->getStatus());

            // 4) Eat the pizza
            $pizza->eatSlice();

            $this->assertEquals(7, $pizza->getSlicesRemaining());
            $this->assertEquals(Pizza::STATUS_PARTLY_EATEN, $pizza->getStatus());

            while ($pizza->getSlicesRemaining()) {
                $pizza->eatSlice();
            }
            $this->assertEquals(Pizza::STATUS_ALL_EATEN, $pizza->getStatus());


            // 5) Verify can't eat an eaten pizza
            $gotException = 'no exception thrown';
            try {
                $pizza->eatSlice();
            } catch (BadFunctionCallException $e) {
                $gotException = 'exception was thrown';
            }
            $this->assertEquals('exception was thrown', $gotException);

        } finally {
            DB::connection(env('DB_CONNECTION'))->rollBack();
        }
    }

    // todo create test
    public function testMargheritaAndHawaiian(): void
    {

    }
}
