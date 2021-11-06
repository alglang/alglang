<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomeTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function the_site_title_appears_on_the_home_screen()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('alglang.net');
        });
    }

    /**
     * @test
     * @group navigation
     */
    public function it_can_navigate_to_the_verb_form_information_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Verb forms')
                    ->assertPathIs('/verb-forms');
        });
    }

    /**
     * @test
     * @group navigation
     */
    public function it_can_navigate_to_the_nominal_form_information_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Nominal forms')
                    ->assertPathIs('/nominal-forms');
        });
    }

    /**
     * @test
     * @group navigation
     */
    public function it_can_navigate_to_the_phonology_information_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Phonology')
                    ->assertPathIs('/phonology');
        });
    }

    /**
     * @test
     * @group navigation
     */
    public function it_can_navigate_to_the_bibliography()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Bibliography')
                    ->assertPathIs('/bibliography');
        });
    }

    /**
     * @test
     * @group navigation
     */
    public function it_can_navigate_to_the_algonquian_languages_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Bibliography')
                    ->assertPathIs('/bibliography');
        });
    }

    /**
     * @test
     * @group navigation
     */
    public function it_can_navigate_to_the_about_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('About')
                    ->assertPathIs('/about');
        });
    }
}
