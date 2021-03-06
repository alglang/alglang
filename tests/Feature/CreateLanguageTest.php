<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Group;
use App\Models\Language;
use App\Models\Morpheme;
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
        $this->group = Group::factory()->create();
        $this->parent = Language::factory()->create();

        $this->contributor = User::factory()->create();
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
        $this->assertEquals(['lat' => 52, 'lng' => 46], $language->position);
        $this->assertEquals(
            'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod',
            $language->notes
        );
    }

    /** @test */
    public function the_created_language_is_returned_in_the_response()
    {
        $group = Group::factory()->create(['name' => 'Test Group']);
        $parent = Language::factory()->create(['name' => 'Test Parent']);
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
        $user = User::factory()->create();

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
        $existingMorphemes = \DB::table(with(new Morpheme)->getTable())->count();

        $response = $this->actingAs($this->contributor)
                        ->postJson('/api/languages', [
                            'name' => 'Test Language',
                            'code' => 'TL',
                            'group_name' => $this->group->name,
                            'parent_code' => $this->parent->code
                        ]);

        $this->assertDatabaseHas(with(new Language)->getTable(), ['code' => 'TL']);
        $this->assertDatabaseCount(with(new Morpheme)->getTable(), $existingMorphemes + 2);
        $this->assertDatabaseHas(with(new Morpheme)->getTable(), [
            'shape' => 'V-',
            'slot_abv' => 'STM',
            'gloss' => 'V',
            'language_code' => 'TL'
        ]);
        $this->assertDatabaseHas(with(new Morpheme)->getTable(), [
            'shape' => 'N-',
            'slot_abv' => 'STM',
            'gloss' => 'N',
            'language_code' => 'TL'
        ]);
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
        Language::factory()->create(['name' => 'Test Language', 'code' => 'XX']);

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
