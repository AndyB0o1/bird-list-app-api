<?php

namespace Tests\Feature;

use App\Models\Bird;
use Database\Factories\BirderFactory;
use App\Models\Birder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;

class BirderTest extends TestCase
{
   Use DatabaseMigrations;

   public function test_getAllBirders_success(): void
    {
        Birder::factory()->count(3)->create();

        $response = $this->getJson('api/birders');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success', 'data'])
                    ->has('data', 3, function (AssertableJson $json) {
                        $json->hasAll([
                            'id',
                            'username',
                            'password'
                        ]);
                    });
            });
    }

    public function test_birdersWithBirds_success(): void
    {
        Birder::factory()->has(Bird::factory()->count(3))->create();

        $response = $this->getJson('api/birders/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success', 'data'])
                    ->has('data', function (AssertableJson $json) {
                        $json->hasAll([
                            'id',
                            'username',
                            'password'
                        ]);
                        $json->has('birds', 3, function (AssertableJson $json) {
                            $json->hasAll([
                                'id',
                                'name',
                                'image',
                                'location',
                                'lat',
                                'lon',
                                'birder_id',
                                'deleted_at'
                            ]);
                        });
                    });
            });
    }

    public function test_addBirder_success(): void
    {
        $testData = [
            'username' => 'BobbyBirder',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/birders', $testData);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseHas('birders', $testData);
    }

    public function test_addBirderAllData_failure(): void
    {
        $testData = [
            'username' => '',
            'password' => ''
        ];

        $response = $this->postJson('/api/birders', $testData);

        $response->assertStatus(422)
            ->assertInvalid([
                'username' => 'The username field is required.'
            ]);

        $this->assertDatabaseMissing('birders', [
            'username' => '',
            'password' => ''
        ]);
    }
}
