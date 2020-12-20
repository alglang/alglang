<?php

namespace Tests\Unit\Http\Livewire\Collections;

use App\Http\Livewire\Collections\Clusters;
use App\Models\ConsonantFeatureSet;
use App\Models\Language;
use App\Models\Phoneme;
use App\Models\Reflex;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Testing\TestableLivewire;
use Tests\TestCase;

class ClustersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->pa = Language::factory()->create(['code' => 'PA']);
        $this->paCluster = Phoneme::factory()->cluster()->create(['language_code' => $this->pa, 'shape' => 'parentxy']);
    }

    public function navigateToClustersComponent(Language $language): TestableLivewire
    {
        $view = $this->livewire(Clusters::class, ['model' => $language]);
        $view->emit('tabChanged', 'clusters');
        return $view;
    }

    /** @test */
    public function it_loads_data_when_the_tab_changes_to_clusters(): void
    {
        $language = Language::factory()->create();
        Phoneme::factory()->cluster()->create(['language_code' => $language, 'shape' => 'clusterx']);
        $view = $this->livewire(Clusters::class, ['model' => $language]);
        $view->assertDontSee('clusterx');

        $view->emit('tabChanged', 'clusters');

        $view->assertSee('clusterx');
    }

    /** @test */
    public function it_shows_clusters()
    {
        $language = Language::factory()->create();
        Phoneme::factory()->cluster()->create(['language_code' => $language, 'shape' => 'clusterx']);

        $view = $this->navigateToClustersComponent($language);

        $view->assertSee('clusterx');
    }

    /** @test */
    public function first_segments_are_displayed_in_order()
    {
        $language = Language::factory()->create();

        Phoneme::factory()->cluster([
            'first_segment_id' => ConsonantFeatureSet::factory()->create(['order_key' => 2, 'shape' => 'phonA'])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->cluster([
            'first_segment_id' => ConsonantFeatureSet::factory()->create(['order_key' => 4, 'shape' => 'phonB'])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->cluster([
            'first_segment_id' => ConsonantFeatureSet::factory()->create(['order_key' => 1, 'shape' => 'phonC'])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->cluster([
            'first_segment_id' => ConsonantFeatureSet::factory()->create(['order_key' => 3, 'shape' => 'phonD'])
        ])->create(['language_code' => $language]);

        $view = $this->navigateToClustersComponent($language);

        $view->assertSeeInOrder(['phonC', 'phonA', 'phonD', 'phonB']);
    }

    /** @test */
    public function second_segments_are_displayed_in_order()
    {
        $language = Language::factory()->create();

        Phoneme::factory()->cluster([
            'second_segment_id' => ConsonantFeatureSet::factory()->create(['order_key' => 2, 'shape' => 'phonA'])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->cluster([
            'second_segment_id' => ConsonantFeatureSet::factory()->create(['order_key' => 4, 'shape' => 'phonB'])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->cluster([
            'second_segment_id' => ConsonantFeatureSet::factory()->create(['order_key' => 1, 'shape' => 'phonC'])
        ])->create(['language_code' => $language]);
        Phoneme::factory()->cluster([
            'second_segment_id' => ConsonantFeatureSet::factory()->create(['order_key' => 3, 'shape' => 'phonD'])
        ])->create(['language_code' => $language]);

        $view = $this->navigateToClustersComponent($language);

        $view->assertSeeInOrder(['phonC', 'phonA', 'phonD', 'phonB']);
    }

    /** @test */
    public function it_shows_proto_algonquian_consonant_reflexes()
    {
        $language = Language::factory()->create();
        $cluster = Phoneme::factory()->cluster()->create(['language_code' => $language, 'shape' => 'childxy']);
        Reflex::factory()->create([
            'phoneme_id' => $this->paCluster,
            'reflex_id' => $cluster
        ]);

        $view = $this->navigateToClustersComponent($language);

        $view->assertSeeInOrder(['Reflexes of Proto-Algonquian clusters', 'parentxy', '>', 'childxy'], false);
    }

    /** @test */
    public function cluster_reflexes_can_be_consonants()
    {
        $language = Language::factory()->create();
        $consonant = Phoneme::factory()->consonant()->create(['language_code' => $language, 'shape' => 'childx']);
        Reflex::factory()->create([
            'phoneme_id' => $this->paCluster,
            'reflex_id' => $consonant
        ]);

        $view = $this->navigateToClustersComponent($language);

        $view->assertSeeInOrder(['Reflexes of Proto-Algonquian clusters', 'parentxy', '>', 'childx'], false);
    }

    /** @test */
    public function cluster_parents_must_be_clusters()
    {
        $language = Language::factory()->create();
        $cluster = Phoneme::factory()->cluster()->create(['language_code' => $language, 'shape' => 'childx']);
        Reflex::factory()->create([
            'phoneme_id' => Phoneme::factory()->consonant()->create(['language_code' => 'PA']),
            'reflex_id' => $cluster
        ]);

        $view = $this->navigateToClustersComponent($language);

        $view->assertDontSee('Reflexes of Proto-Algonquian clusters');
    }

    /** @test */
    public function it_does_not_show_proto_algonquian_cluster_reflexes_if_it_has_none()
    {
        $language = Language::factory()->create();

        $view = $this->navigateToClustersComponent($language);

        $view->assertDontSee('Reflexes of Proto-Algonquian clusters');
    }

    /** @test */
    public function it_shows_missing_proto_algonquian_cluster_reflexes_if_it_has_at_least_one_reflex()
    {
        $language = Language::factory()->create();

        Reflex::factory()->create([
            'phoneme_id' => Phoneme::factory()->cluster()->create(['language_code' => 'PA']),
            'reflex_id' => Phoneme::factory()->cluster()->create(['language_code' => $language])
        ]);

        $view = $this->navigateToClustersComponent($language);

        $view->assertSeeInOrder(['Reflexes of Proto-Algonquian clusters', 'parentxy', '>', '?'], false);
    }

    /** @test */
    public function proto_algonquian_reflexes_are_not_shown_on_the_proto_algonquian_page()
    {
        $view = $this->navigateToClustersComponent($this->pa);

        $view->assertDontSee('Reflexes');
    }
}
