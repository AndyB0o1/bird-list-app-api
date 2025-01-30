<?php

namespace Tests\Feature;

use App\Models\Bird;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BirdTest extends TestCase
{
    Use DatabaseMigrations;

    public function test_getAllBirds_success(): void
    {
        Bird::factory()->count(5)->has(User::factory()->count(1))->create();

        $response = $this->getJson('api/birds');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success', 'data'])
                    ->has('data', 5, function (AssertableJson $json) {
                        $json->hasAll([
                            'id',
                            'name',
                            'image',
                            'location',
                            'lat',
                            'lon',
                            'user_id',
                            'deleted_at'
                        ]);
                        $json->has('user');
                        });
                    });
    }

    public function test_getSingleBird_success(): void
    {
        Bird::factory()->has(User::factory())->create();

        $response = $this->getJson('api/birds/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success', 'data'])
                    ->has('data', function (AssertableJson $json) {
                        $json->hasAll([
                            'id',
                            'name',
                            'image',
                            'location',
                            'lat',
                            'lon',
                            'user_id',
                            'deleted_at'
                        ]);
                    });
            });
    }

    public function test_addBird_success(): void
    {
        User::factory()->create();

        $testData = [
            'name' => 'Cassowary',
            'image' => '/image.png',
            'location' => 'Oz',
            'lat' => 50.1,
            'lon' => -2.4,
            'user_id' => 1,
            'deleted_at' => null
        ];

        $response = $this->postJson('/api/birds', $testData);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseHas('birds', $testData);
    }

    public function test_addBirdAllData_failure(): void
    {
        $testData = [
            'name' => '',
            'image' => '',
            'location' => '',
            'lat' => 'th',
            'lon' => 'ab',
            'user_id' => 1,
            'deleted_at' => null
        ];

        $response = $this->postJson('api/birds', $testData);

        $response->assertStatus(422)
            ->assertInvalid([
                'name' => 'The name field is required.',
                'image' => 'The image field must be a string.',
                'location' => 'The location field must be a string.',
                'lat' => 'The lat field must be a number.',
                'lon' => 'The lon field must be a number.',
                'user_id' => 'The selected user id is invalid.'
            ]);

        $this->assertDatabaseMissing('birds', [
            'name' => '',
            'image' => '',
            'location' => '',
            'lat' => 'th',
            'lon' => 'ab',
            'user_id' => 1,
            'deleted_at' => null
        ]);
    }

    public function test_editBird_success(): void
    {
        User::factory()->create();
        Bird::factory()->create();

        $response = $this->putJson('/api/birds/1', [
            'name' => 'Bob',
            'image' => 'an image',
            'location' => 'Nowhere',
            'lat' => 20,
            'lon' => -20,
            'user_id' => 1,
            'deleted_at' => null
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('birds', [
                'name' => 'Bob',
                'image' => 'an image',
                'location' => 'Nowhere',
                'lat' => 20,
                'lon' => -20,
                'user_id' => 1,
                'deleted_at' => null
            ]);

    }

    public function test_deleteBird_success(): void
    {
        $bird = Bird::factory()->create();

        $bird->delete();

        $this->assertSoftDeleted($bird);
    }
}
