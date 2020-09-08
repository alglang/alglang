<?php

namespace Tests\Unit\Models;

use App\Models\VerbOrder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerbOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function orders_are_ordered_by_order_key_by_default()
    {
        VerbOrder::factory()->create(['name' => 'Bar', 'order_key' => 2]);
        VerbOrder::factory()->create(['name' => 'Foo', 'order_key' => 1]);

        $orders = VerbOrder::all();

        $this->assertEquals(['Foo', 'Bar'], $orders->pluck('name')->toArray());
    }
}
