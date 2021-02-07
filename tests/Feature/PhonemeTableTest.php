<?php

namespace Tests\Feature;

use App\Models\Phoneme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PhonemeTableTest extends TestCase
{
    protected function itemFactory(array $data = []): object
    {
        $default = [
            'col' => (object)['name' => ''],
            'row' => (object)['name' => ''],
            'shape' => '',
            'url' => ''
        ];

        return new Phoneme(array_merge($default, $data));
    }

    /** @test */
    public function it_shows_column_headers()
    {
        $items = collect([
            $this->itemFactory(['col' => (object)['name' => 'Col1']]),
            $this->itemFactory(['col' => (object)['name' => 'Col2']])
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="col" row-key="row" />', compact('items'));

        $view->assertSee('Col1');
        $view->assertSee('Col2');
    }

    /** @test */
    public function it_can_use_an_alternate_column_accessor()
    {
        $items = collect([
            $this->itemFactory(['col' => (object)['shape' => 'Col1']]),
            $this->itemFactory(['col' => (object)['shape' => 'Col2']])
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="col" col-accessor="shape" row-key="row" />', compact('items'));

        $view->assertSee('Col1');
        $view->assertSee('Col2');
    }

    /** @test */
    public function column_headers_are_ordered_by_key()
    {
        $items = collect([
            $this->itemFactory(['col' => (object)['name' => 'Col1', 'order_key' => 2]]),
            $this->itemFactory(['col' => (object)['name' => 'Col2', 'order_key' => 1]]),
            $this->itemFactory(['col' => (object)['name' => 'Col3', 'order_key' => 4]]),
            $this->itemFactory(['col' => (object)['name' => 'Col4', 'order_key' => 3]]),
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="col" row-key="row" />', compact('items'));

        $view->assertSeeInOrder(['Col2', 'Col1', 'Col4', 'Col3']);
    }

    /** @test */
    public function it_can_use_an_alternate_column_order_key()
    {
        $items = collect([
            $this->itemFactory(['col' => (object)['name' => 'Col1', 'foo' => 2]]),
            $this->itemFactory(['col' => (object)['name' => 'Col2', 'foo' => 1]]),
            $this->itemFactory(['col' => (object)['name' => 'Col3', 'foo' => 4]]),
            $this->itemFactory(['col' => (object)['name' => 'Col4', 'foo' => 3]]),
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="col" col-order-key="foo" row-key="row" />', compact('items'));

        $view->assertSeeInOrder(['Col2', 'Col1', 'Col4', 'Col3']);
    }

    /** @test */
    public function it_includes_column_labels_in_cell_data()
    {
        $items = collect([
            $this->itemFactory(['features' => [
                'theCol' => (object)['name' => 'Col1']],
            ]),
            $this->itemFactory(['features' => [
                'theCol' => (object)['name' => 'Col2']],
            ]),
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="features.theCol" row-key="row" />', compact('items'));

        $view->assertSee('data-the-col="Col1"', false);
        $view->assertSee('data-the-col="Col2"', false);
    }

    /** @test */
    public function it_shows_row_headers()
    {
        $items = collect([
            $this->itemFactory(['row' => (object)['name' => 'Row1']]),
            $this->itemFactory(['row' => (object)['name' => 'Row2']])
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="col" row-key="row" />', compact('items'));

        $view->assertSee('Row1');
        $view->assertSee('Row2');
    }

    /** @test */
    public function it_can_use_an_alternate_row_accessor()
    {
        $items = collect([
            $this->itemFactory(['row' => (object)['shape' => 'Row1']]),
            $this->itemFactory(['row' => (object)['shape' => 'Row2']])
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="col" row-accessor="shape" row-key="row" />', compact('items'));

        $view->assertSee('Row1');
        $view->assertSee('Row2');
    }

    /** @test */
    public function row_headers_are_ordered_by_key()
    {
        $items = collect([
            $this->itemFactory(['row' => (object)['name' => 'Row1', 'order_key' => 2]]),
            $this->itemFactory(['row' => (object)['name' => 'Row2', 'order_key' => 1]]),
            $this->itemFactory(['row' => (object)['name' => 'Row3', 'order_key' => 4]]),
            $this->itemFactory(['row' => (object)['name' => 'Row4', 'order_key' => 3]]),
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="col" row-key="row" />', compact('items'));

        $view->assertSeeInOrder(['Row2', 'Row1', 'Row4', 'Row3']);
    }

    /** @test */
    public function it_can_use_an_alternate_row_order_key()
    {
        $items = collect([
            $this->itemFactory(['row' => (object)['name' => 'Row1', 'foo' => 2]]),
            $this->itemFactory(['row' => (object)['name' => 'Row2', 'foo' => 1]]),
            $this->itemFactory(['row' => (object)['name' => 'Row3', 'foo' => 4]]),
            $this->itemFactory(['row' => (object)['name' => 'Row4', 'foo' => 3]]),
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="col" row-key="row" row-order-key="foo" />', compact('items'));

        $view->assertSeeInOrder(['Row2', 'Row1', 'Row4', 'Row3']);
    }

    /** @test */
    public function it_includes_row_labels_in_cell_data()
    {
        $items = collect([
            $this->itemFactory(['features' => [
                'theRow' => (object)['name' => 'Row1']]
            ]),
            $this->itemFactory(['features' => [
                'theRow' => (object)['name' => 'Row2']]
            ])
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="col" row-key="features.theRow" />', compact('items'));

        $view->assertSee('data-the-row="Row1"', false);
        $view->assertSee('data-the-row="Row2"', false);
    }

    /** @test */
    public function it_shows_all_values()
    {
        $items = collect([
            $this->itemFactory([
                'col' => (object)['name' => 'Col1'],
                'row' => (object)['name' => 'Row1'],
                'shape' => 'Item1'
            ]),
            $this->itemFactory([
                'col' => (object)['name' => 'Col1'],
                'row' => (object)['name' => 'Row2'],
                'shape' => 'Item2',
            ]),
            $this->itemFactory([
                'col' => (object)['name' => 'Col1'],
                'row' => (object)['name' => 'Row2'],
                'shape' => 'Item3',
            ]),
            $this->itemFactory([
                'col' => (object)['name' => 'Col3'],
                'row' => (object)['name' => 'Row3'],
                'shape' => 'Item4',
            ])
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="col" row-key="row" />', compact('items'));

        $view->assertSee('Item1');
        $view->assertSee('Item2');
        $view->assertSee('Item3');
        $view->assertSee('Item4');
    }
}
