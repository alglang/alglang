<?php

namespace Tests\Unit;

use App\Models\Form;
use App\Models\Language;
use App\Models\Morpheme;
use App\Models\MorphemeConnection;
use App\Models\VerbFeature;
use App\Models\VerbStructure;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class FormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_does_not_generate_a_url_without_an_acceptable_structure()
    {
        $form = new Form(['structure_type' => 'foo']);

        $this->expectException(\UnexpectedValueException::class);

        $form->url;
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
        $form = factory(Form::class)->create(['language_code' => factory(Language::class)->create()->code]);

        $morphemes = [
            factory(Morpheme::class)->create([
                'language_code' => $form->language_code,
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
        $form = factory(Form::class)->create(['language_code' => factory(Language::class)->create()->code]);
        $morpheme1 = factory(Morpheme::class)->create([
            'language_code' => $form->language_code,
            'shape' => '-bar'
        ]);
        $morpheme2 = factory(Morpheme::class)->create([
            'language_code' => $form->language_code,
            'shape' => 'foo-'
        ]);

        $form->morphemeConnections()->create(['morpheme_id' => $morpheme1->id, 'position' => 1]);
        $form->morphemeConnections()->create(['morpheme_id' => $morpheme2->id, 'position' => 0]);

        $this->assertEquals(['foo-', '-bar'], $form->morphemes->pluck('shape')->toArray());
    }

    /** @test */
    public function it_replaces_old_morpheme_connections()
    {
        $form = factory(Form::class)->create(['language_code' => factory(Language::class)->create()->code]);
        $morpheme1 = factory(Morpheme::class)->create([
            'language_code' => $form->language_code,
            'shape' => 'foo-'
        ]);
        $morpheme2 = factory(Morpheme::class)->create([
            'language_code' => $form->language_code,
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

    /** @test */
    public function it_caches_the_morphemes_attribute()
    {
        $form = factory(Form::class)->create();

        DB::connection()->enableQueryLog();

        $form->morphemes;
        $queryCount = count(DB::getQueryLog());

        $form->morphemes;
        $this->assertCount($queryCount, DB::getQueryLog());

        DB::connection()->disableQueryLog();
    }
}
