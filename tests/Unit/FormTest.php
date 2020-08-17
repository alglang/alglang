<?php

namespace Tests\Unit;

use App\Form;
use App\Language;
use App\Morpheme;
use App\MorphemeConnection;
use App\VerbFeature;
use App\VerbStructure;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function language_is_always_eager_loaded()
    {
        factory(Form::class)->create();
        $form = Form::first();

        $this->assertTrue($form->relationLoaded('language'));
    }

    /*
    |--------------------------------------------------------------------------
    | Morpheme connections
    |--------------------------------------------------------------------------
    |
    */

    /** @test */
    public function it_can_assign_morphemes()
    {
        $form = factory(Form::class)->create(['language_id' => factory(Language::class)->create()->id]);

        $morphemes = [
            factory(Morpheme::class)->create([
                'language_id' => $form->language_id,
                'shape' => 'foo-'
            ]),
            'bar'
        ];

        $form->assignMorphemes($morphemes);

        $this->assertEquals(['foo-', 'bar'], $form->morphemes->pluck('shape')->toArray());
    }

    /** @test */
    public function it_retrieves_its_morphemes_in_order()
    {
        $form = factory(Form::class)->create(['language_id' => factory(Language::class)->create()->id]);
        $morpheme1 = factory(Morpheme::class)->create([
            'language_id' => $form->language_id,
            'shape' => '-bar'
        ]);
        $morpheme2 = factory(Morpheme::class)->create([
            'language_id' => $form->language_id,
            'shape' => 'foo-'
        ]);

        $form->morphemeConnections()->create(['morpheme_id' => $morpheme1->id, 'position' => 1]);
        $form->morphemeConnections()->create(['morpheme_id' => $morpheme2->id, 'position' => 0]);

        $this->assertEquals(['foo-', '-bar'], $form->morphemes->pluck('shape')->toArray());
    }

    /** @test */
    public function it_replaces_old_morpheme_connections()
    {
        $form = factory(Form::class)->create(['language_id' => factory(Language::class)->create()->id]);
        $morpheme1 = factory(Morpheme::class)->create([
            'language_id' => $form->language_id,
            'shape' => 'foo-'
        ]);
        $morpheme2 = factory(Morpheme::class)->create([
            'language_id' => $form->language_id,
            'shape' => 'bar-'
        ]);

        $preexistingConnections = MorphemeConnection::count();

        $form->assignMorphemes([$morpheme1]);
        $this->assertEquals($preexistingConnections + 1, MorphemeConnection::count());
        $this->assertEquals('foo-', $form->morphemes->first()->shape);

        $form->assignMorphemes([$morpheme2]);
        $this->assertEquals($preexistingConnections + 1, MorphemeConnection::count());
        $this->assertEquals('bar-', $form->fresh()->morphemes->first()->shape);
    }
}
