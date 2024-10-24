<?php

namespace Tests\Feature;

use App\Models\Bird;
use App\Models\Birder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BirdTest extends TestCase
{
    Use DatabaseMigrations;

    public function test_getAllBirds_success(): void
    {
        Bird::factory()->count(5)->has(Birder::factory()->count(1))->create();

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
                            'birder_id'
                        ]);
                        $json->has('birder');
                        });
                    });
    }
}
