<?php

namespace Tests\Unit;

use App\Language;
use App\VerbForm;
use App\Http\Resources\VerbFormCollection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerbFormCollectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_from_a_language()
    {
        $language1 = factory(Language::class)->create();
        $language2 = factory(Language::class)->create();

        $verbForm1 = factory(VerbForm::class)->create([
            'shape' => 'V-a',
            'language_id' => $language1
        ]);

        $verbForm2 = factory(VerbForm::class)->create([
            'shape' => 'V-b',
            'language_id' => $language2
        ]);

        $collection = VerbFormCollection::fromLanguage($language1);

        $this->assertCount(1, $collection->collection);
        $this->assertEquals('V-a', $collection->collection[0]->shape);
    }
}
