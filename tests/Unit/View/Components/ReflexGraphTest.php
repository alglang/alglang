<?php

namespace Tests\Unit\View\Components;

use App\Models\Phoneme;
use App\Models\Reflex;
use Tests\TestCase;

class ReflexGraphTest extends TestCase
{
    /** @test */
    public function it_has_the_root_class_when_the_parent_and_child_flags_are_unspecified()
    {
        $view = $this->blade('<x-reflex-graph />');

        $view->assertSee('root');
    }

    /** @test */
    public function it_shows_its_phoneme()
    {
        $phoneme = new Phoneme([
            'shape' => 'phonemeshape',
            'language_code' => 'TL'
        ]);

        $view = $this->blade('<x-reflex-graph :phoneme="$phoneme" />', compact('phoneme'));

        $view->assertSee('phonemeshape');
        $view->assertSee('TL');
    }

    /** @test */
    public function it_shows_its_phonemes_environment_if_it_has_one()
    {
        $phoneme = new Phoneme([
            'pivot' => new Reflex(['environment' => 'whenever'])
        ]);

        $view = $this->blade('<x-reflex-graph :phoneme="$phoneme" :root="false" />', compact('phoneme'));

        $view->assertSee('whenever');
    }

    /** @test */
    public function it_shows_its_phonemes_parents_if_its_parents_flag_is_unspecified()
    {
        $phoneme = new Phoneme([
            'parents' => collect([
                new Phoneme([
                    'shape' => 'theparent',
                    'pivot' => new Reflex([
                        'environment' => 'wherever'
                    ])
                ])
            ])
        ]);

        $view = $this->blade('<x-reflex-graph :phoneme="$phoneme" />', compact('phoneme'));

        $view->assertSee('theparent');
        $view->assertSee('wherever');
    }

    /** @test */
    public function it_does_not_show_its_phonemes_parents_if_its_parents_flag_is_false()
    {
        $phoneme = new Phoneme([
            'parents' => collect([
                new Phoneme(['shape' => 'theparent'])
            ])
        ]);

        $view = $this->blade('<x-reflex-graph :phoneme="$phoneme" :show-parents="false" />', compact('phoneme'));

        $view->assertDontSee('root');
        $view->assertDontSee('theparent');
    }

    /** @test */
    public function it_does_not_show_its_childrens_parents_if_its_parents_flag_is_false()
    {
        $phoneme = new Phoneme([
            'children' => collect([
                new Phoneme([
                    'parents' => collect([
                        new Phoneme(['shape' => 'theparent'])
                    ])
                ])
            ])
        ]);

        $view = $this->blade('<x-reflex-graph :phoneme="$phoneme" :show-parents="false" />', compact('phoneme'));

        $view->assertDontSee('theparent');
    }

    /** @test */
    public function it_shows_its_phonemes_children_if_its_children_flag_is_unspecified()
    {
        $phoneme = new Phoneme([
            'children' => collect([
                new Phoneme([
                    'shape' => 'thechild',
                    'pivot' => new Reflex([
                        'environment' => 'wherever'
                    ])
                ])
            ])
        ]);

        $view = $this->blade('<x-reflex-graph :phoneme="$phoneme" />', compact('phoneme'));

        $view->assertSee('thechild');
        $view->assertSee('wherever');
    }

    /** @test */
    public function it_does_not_show_its_phonemes_children_if_its_children_flag_is_false()
    {
        $phoneme = new Phoneme([
            'children' => collect([
                new Phoneme(['shape' => 'thechild'])
            ])
        ]);

        $view = $this->blade('<x-reflex-graph :phoneme="$phoneme" :show-children="false" />', compact('phoneme'));

        $view->assertDontSee('root');
        $view->assertDontSee('thechild');
    }

    /** @test */
    public function it_does_not_show_its_parents_children_if_its_children_flag_is_false()
    {
        $phoneme = new Phoneme([
            'parents' => collect([
                new Phoneme([
                    'shape' => 'theparent',
                    'children' => collect([
                        new Phoneme(['shape' => 'thechild'])
                    ])
                ])
            ])
        ]);

        $view = $this->blade('<x-reflex-graph :phoneme="$phoneme" :show-children="false" />', compact('phoneme'));

        $view->assertDontSee('thechild');
    }
}
