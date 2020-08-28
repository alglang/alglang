<?php

namespace Tests\Unit;

use App\Models\VerbOrder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerbOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function orders_are_ordered_by_order_key_by_default()
    {
        factory(VerbOrder::class)->create(['name' => 'Bar', 'order_key' => 2]);
        factory(VerbOrder::class)->create(['name' => 'Foo', 'order_key' => 1]);

        $orders = VerbOrder::all();

        $this->assertEquals(['Foo', 'Bar'], $orders->pluck('name')->toArray());
    }
}
