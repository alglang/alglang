<?php

namespace Tests\Unit\Http\Livewire;

use App\Http\Livewire\Map;
use Tests\TestCase;

class MapTest extends TestCase
{
    /** @test */
    public function the_borders_can_be_toggled()
    {
        $view = $this->livewire(Map::class, ['locations' => []]);

        $view->call('toggleBorders');

        $view->assertSet('showBorders', true);
    }
}
