<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Http\Livewire\Collections\Clusters;
use App\Models\Language;
use App\Models\Phoneme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClustersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_clusters()
    {
        $language = Language::factory()->create();
        Phoneme::factory()->cluster()->create(['language_code' => $language, 'shape' => 'clusterx']);

        $view = $this->livewire(Clusters::class, ['model' => $language]);

        $view->assertSee('clusterx');
    }

    /** @test */
    public function first_segments_are_displayed_in_order()
    {
        $language = Language::factory()->create();

        Phoneme::factory()->cluster([
            'first_segment_id' => Phoneme::factory()->create([
                'shape' => 'phonA',
                'order_key' => 2
            ]),
        ])->create(['language_code' => $language]);
        Phoneme::factory()->cluster([
            'first_segment_id' => Phoneme::factory()->create([
                'shape' => 'phonB',
                'order_key' => 4
            ])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->cluster([
            'first_segment_id' => Phoneme::factory()->create([
                'shape' => 'phonC',
                'order_key' => 1
            ])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->cluster([
            'first_segment_id' => Phoneme::factory()->create([
                'shape' => 'phonD',
                'order_key' => 3
            ])
        ])->create(['language_code' => $language]);

        $view = $this->livewire(Clusters::class, ['model' => $language]);

        $view->assertSeeInOrder(['phonC', 'phonA', 'phonD', 'phonB']);
    }

    /** @test */
    public function second_segments_are_displayed_in_order()
    {
        $language = Language::factory()->create();

        Phoneme::factory()->cluster([
            'second_segment_id' => Phoneme::factory()->create([
                'shape' => 'phonA',
                'order_key' => 2
            ]),
        ])->create(['language_code' => $language]);
        Phoneme::factory()->cluster([
            'second_segment_id' => Phoneme::factory()->create([
                'shape' => 'phonB',
                'order_key' => 4
            ])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->cluster([
            'second_segment_id' => Phoneme::factory()->create([
                'shape' => 'phonC',
                'order_key' => 1
            ])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->cluster([
            'second_segment_id' => Phoneme::factory()->create([
                'shape' => 'phonD',
                'order_key' => 3
            ])
        ])->create(['language_code' => $language]);

        $view = $this->livewire(Clusters::class, ['model' => $language]);

        $view->assertSeeInOrder(['phonC', 'phonA', 'phonD', 'phonB']);
    }
}
