<?php

namespace Tests\Feature;

use App\User;
use App\Group;
use App\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateLanguageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->withPermissions();
        $this->group = factory(Group::class)->create();
        $this->parent = factory(Language::class)->create();

        $this->contributor = factory(User::class)->create();
        $this->contributor->assignRole('contributor');
    }

    /** @test */
    public function a_contributor_can_create_a_language()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 'TL',
                            'group_id' => $this->group->id,
                            'parent_id' => $this->parent->id,
                            'reconstructed' => true,
                            'position' => '{"lat":52,"lng":46}',
                            'notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod'
                        ]);

        $response->assertRedirect();
        $language = Language::where(['algo_code' => 'TL'])->first();

        $this->assertNotNull($language);
        $response->assertRedirect($language->url);

        $this->assertEquals('Test Language', $language->name);
        $this->assertEquals('TL', $language->algo_code);
        $this->assertEquals($this->group->id, $language->group_id);
        $this->assertEquals($this->parent->id, $language->parent_id);
        $this->assertTrue($language->reconstructed);
        $this->assertEquals((object) ['lat' => 52, 'lng' => 46], $language->position);
        $this->assertEquals(
            'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod',
            $language->notes
        );
    }

    /** @test */
    public function a_guest_cannot_add_a_language()
    {
        $this->assertGuest();

        $response = $this->postJson('/api/languages', [
            'name' => 'Test Language',
            'algo_code' => 'TL',
            'group_id' => $this->group->id,
            'parent_id' => $this->parent->id
        ]);

        $response->assertUnauthorized();
        $this->assertNull(Language::where(['algo_code' => 'TL'])->first());
    }

    /** @test */
    public function a_user_cannot_add_a_language_without_permission()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 'TL',
                            'group_id' => $this->group->id,
                            'parent_id' => $this->parent->id
                        ]);

        $response->assertForbidden();
        $this->assertNull(Language::where(['algo_code' => 'TL'])->first());
    }

    /** @test */
    public function placeholder_morphemes_are_generated_with_the_language()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 'TL',
                            'group_id' => $this->group->id,
                            'parent_id' => $this->parent->id
                        ]);

        $language = Language::where(['algo_code' => 'TL'])->first();
        $this->assertCount(2, $language->morphemes);

        $this->assertEquals('V-', $language->morphemes[0]->shape);
        $this->assertEquals('STM', $language->morphemes[0]->slot_abv);
        $this->assertEquals('V', $language->morphemes[0]->gloss);

        $this->assertEquals('N-', $language->morphemes[1]->shape);
        $this->assertEquals('STM', $language->morphemes[1]->slot_abv);
        $this->assertEquals('N', $language->morphemes[1]->gloss);
    }

    /** @test */
    public function language_name_must_be_included_in_the_request()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'algo_code' => 'TL',
                            'group_id' => $this->group->id,
                            'parent_id' => $this->parent->id
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function language_name_must_be_a_string()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'algo_code' => 'TL',
                            'group_id' => $this->group->id,
                            'parent_id' => $this->parent->id
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function the_language_name_must_be_unique()
    {
        factory(Language::class)->create(['name' => 'Test Language', 'algo_code' => 'XX']);

        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 'TL',
                            'group_id' => $this->group->id,
                            'parent_id' => $this->parent->id
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function algo_code_must_be_included_in_the_request()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'group_id' => $this->group->id,
                            'parent_id' => $this->parent->id
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('algo_code');
    }

    /** @test */
    public function algo_code_must_be_a_string()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 4,
                            'group_id' => $this->group->id,
                            'parent_id' => $this->parent->id
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('algo_code');
    }

    /** @test */
    public function algo_code_must_have_fewer_than_6_characters()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 'ABCDEF',
                            'group_id' => $this->group->id,
                            'parent_id' => $this->parent->id
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('algo_code');
    }

    /** @test */
    public function group_id_must_be_included_in_the_request()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 'TL',
                            'parent_id' => $this->parent->id
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('group_id');
    }

    /** @test */
    public function group_id_must_exist()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 'TL',
                            'group_id' => 440,
                            'parent_id' => $this->parent->id
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('group_id');
    }

    /** @test */
    public function parent_id_must_be_included_in_the_request()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 'TL',
                            'group_id' => $this->group->id
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('parent_id');
    }

    /** @test */
    public function parent_id_must_exist()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 'TL',
                            'group_id' => $this->group->id,
                            'parent_id' => 440
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('parent_id');
    }

    /** @test */
    public function reconstructed_must_be_boolean()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 'TL',
                            'group_id' => $this->group->id,
                            'parent_id' => $this->parent->id,
                            'reconstructed' => 'foo'
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('reconstructed');
    }

    /** @test */
    public function position_must_be_json()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 'TL',
                            'group_id' => $this->group->id,
                            'parent_id' => $this->parent->id,
                            'position' => 'foo'
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('position');
    }

    /** @test */
    public function notes_must_be_text()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'algo_code' => 'TL',
                            'group_id' => $this->group->id,
                            'parent_id' => $this->parent->id,
                            'notes' => 12
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('notes');
    }
}
