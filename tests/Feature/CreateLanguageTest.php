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

    private $group;
    private $parent;
    private $contributor;

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
                            'code' => 'TL',
                            'group_name' => $this->group->name,
                            'parent_code' => $this->parent->code,
                            'reconstructed' => true,
                            'position' => '{"lat":52,"lng":46}',
                            'notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod'
                        ]);

        $response->assertSuccessful();

        $language = Language::where(['code' => 'TL'])->first();

        $this->assertEquals('Test Language', $language->name);
        $this->assertEquals('TL', $language->code);
        $this->assertEquals($this->group->name, $language->group_name);
        $this->assertEquals($this->parent->code, $language->parent_code);
        $this->assertTrue($language->reconstructed);
        $this->assertEquals((object) ['lat' => 52, 'lng' => 46], $language->position);
        $this->assertEquals(
            'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod',
            $language->notes
        );
    }

    /** @test */
    public function the_created_language_is_returned_in_the_response()
    {
        $group = factory(Group::class)->create(['name' => 'Test Group']);
        $parent = factory(Language::class)->create(['name' => 'Test Parent']);
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'code' => 'TL',
                            'group_name' => $group->name,
                            'parent_code' => $parent->code,
                            'reconstructed' => true,
                            'position' => '{"lat":52,"lng":46}',
                            'notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod'
                        ]);

        $response->assertCreated();
        $response->assertJson([
            'name' => 'Test Language',
            'code' => 'TL',
            'group_name' => $group->name,
            'group' => ['name' => 'Test Group'],
            'parent_code' => $parent->code,
            'parent' => ['name' => 'Test Parent'],
            'reconstructed' => true,
            'position' => ['lat' => 52, 'lng' => 46],
            'notes' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod'
        ]);
    }

    /** @test */
    public function a_guest_cannot_add_a_language()
    {
        $this->assertGuest();

        $response = $this->postJson('/api/languages', [
            'name' => 'Test Language',
            'code' => 'TL',
            'group_name' => $this->group->name,
            'parent_code' => $this->parent->code
        ]);

        $response->assertUnauthorized();
        $this->assertNull(Language::where(['code' => 'TL'])->first());
    }

    /** @test */
    public function a_user_cannot_add_a_language_without_permission()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'code' => 'TL',
                            'group_name' => $this->group->name,
                            'parent_code' => $this->parent->code
                        ]);

        $response->assertForbidden();
        $this->assertNull(Language::where(['code' => 'TL'])->first());
    }

    /** @test */
    public function placeholder_morphemes_are_generated_with_the_language()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'code' => 'TL',
                            'group_name' => $this->group->name,
                            'parent_code' => $this->parent->code
                        ]);

        $language = Language::where(['code' => 'TL'])->first();
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
                            'code' => 'TL',
                            'group_name' => $this->group->name,
                            'parent_code' => $this->parent->code
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function language_name_must_be_a_string()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'code' => 'TL',
                            'group_id' => $this->group->id,
                            'parent_code' => $this->parent->code
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function the_language_name_must_be_unique()
    {
        factory(Language::class)->create(['name' => 'Test Language', 'code' => 'XX']);

        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'code' => 'TL',
                            'group_name' => $this->group->name,
                            'parent_code' => $this->parent->code
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function code_must_be_included_in_the_request()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'group_name' => $this->group->name,
                            'parent_code' => $this->parent->code
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('code');
    }

    /** @test */
    public function code_must_be_a_string()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'code' => 4,
                            'group_name' => $this->group->name,
                            'parent_code' => $this->parent->code
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('code');
    }

    /** @test */
    public function code_must_have_fewer_than_6_characters()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'code' => 'ABCDEF',
                            'group_name' => $this->group->name,
                            'parent_code' => $this->parent->code
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('code');
    }

    /** @test */
    public function group_name_must_be_included_in_the_request()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'code' => 'TL',
                            'parent_code' => $this->parent->code
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('group_name');
    }

    /** @test */
    public function group_name()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'code' => 'TL',
                            'group_name' => 440,
                            'parent_code' => $this->parent->code
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('group_name');
    }

    /** @test */
    public function parent_code_must_be_included_in_the_request()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'code' => 'TL',
                            'group_name' => $this->group->name
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('parent_code');
    }

    /** @test */
    public function parent_code_must_exist()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'code' => 'TL',
                            'group_name' => $this->group->name,
                            'parent_code' => 'X'
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('parent_code');
    }

    /** @test */
    public function reconstructed_must_be_boolean()
    {
        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'code' => 'TL',
                            'group_name' => $this->group->name,
                            'parent_code' => $this->parent->code,
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
                            'code' => 'TL',
                            'group_name' => $this->group->name,
                            'parent_code' => $this->parent->code,
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
                            'code' => 'TL',
                            'group_name' => $this->group->name,
                            'parent_code' => $this->parent->code,
                            'notes' => 12
                        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('notes');
    }
}
