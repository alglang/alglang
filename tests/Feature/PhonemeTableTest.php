<?php

namespace Tests\Feature;

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
            'formatted_shape' => '',
            'url' => ''
        ];

        return (object)array_merge($default, $data);
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
    public function it_includes_column_labels_in_cell_data()
    {
        $items = collect([
            $this->itemFactory(['features' => [
                'col' => (object)['name' => 'Col1']],
            ]),
            $this->itemFactory(['features' => [
                'col' => (object)['name' => 'Col2']],
            ]),
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="features.col" row-key="row" />', compact('items'));

        $view->assertSee('data-col="Col1"', false);
        $view->assertSee('data-col="Col2"', false);
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
    public function it_includes_row_labels_in_cell_data()
    {
        $items = collect([
            $this->itemFactory(['features' => [
                'row' => (object)['name' => 'Row1']]
            ]),
            $this->itemFactory(['features' => [
                'row' => (object)['name' => 'Row2']]
            ])
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="col" row-key="features.row" />', compact('items'));

        $view->assertSee('data-row="Row1"', false);
        $view->assertSee('data-row="Row2"', false);
    }

    /** @test */
    public function it_shows_all_values()
    {
        $items = collect([
            $this->itemFactory([
                'col' => (object)['name' => 'Col1'],
                'row' => (object)['name' => 'Row1'],
                'formatted_shape' => 'Item1'
            ]),
            $this->itemFactory([
                'col' => (object)['name' => 'Col1'],
                'row' => (object)['name' => 'Row2'],
                'formatted_shape' => 'Item2',
            ]),
            $this->itemFactory([
                'col' => (object)['name' => 'Col1'],
                'row' => (object)['name' => 'Row2'],
                'formatted_shape' => 'Item3',
            ]),
            $this->itemFactory([
                'col' => (object)['name' => 'Col3'],
                'row' => (object)['name' => 'Row3'],
                'formatted_shape' => 'Item4',
            ])
        ]);

        $view = $this->blade('<x-phoneme-table :items="$items" col-key="col" row-key="row" />', compact('items'));

        $view->assertSee('Item1');
        $view->assertSee('Item2');
        $view->assertSee('Item3');
        $view->assertSee('Item4');
    }
}
