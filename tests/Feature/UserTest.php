<?php

namespace Tests\Feature;

use App\Models\Bird;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    Use DatabaseMigrations;

    public function test_getAllUsers_success(): void
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('api/users');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success', 'data'])
                    ->has('data', 3, function (AssertableJson $json) {
                        $json->hasAll([
                            'id',
                            'name',
                            'email',
                            'password',
                            'email_verified_at',
                            'remember_token',
                            'deleted_at'
                        ]);
                    });
            });
    }

    public function test_usersWithBirds_success(): void
    {
        User::factory()->has(Bird::factory()->count(3))->create();

        $response = $this->getJson('api/users/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success', 'data'])
                    ->has('data', function (AssertableJson $json) {
                        $json->hasAll([
                            'id',
                            'name',
                            'email',
                            'password',
                            'email_verified_at',
                            'remember_token',
                            'deleted_at'
                        ]);
                        $json->has('birds', 3, function (AssertableJson $json) {
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
            });
    }

//    public function test_addUser_success(): void
//    {
//        $testData = [
//            'name' => 'BobbyBirder',
//            'email' => 'test@test.co.uk',
//            'password' => '$2y$04$8jzHZzU3HXyn8OC6mthmgOMAdtbQ9dWajQ4ZoY.\/cGLV93jrjALUS'
//        ];
//
//        $response = $this->postJson('/api/users', $testData);
//
//        $response->assertStatus(201)
//            ->assertJson(function (AssertableJson $json) {
//                $json->hasAll(['message', 'success']);
//            });
//
//        $this->assertDatabaseHas('users', $testData);
//    }

    public function test_addUserAllData_failure(): void
    {
        $testData = [
            'name' => '',
            'email' => '',
            'password' => ''
        ];

        $response = $this->postJson('/api/users', $testData);

        $response->assertStatus(422)
            ->assertInvalid([
                'name' => 'The name field is required.'
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => '',
            'email' => '',
            'password' => ''
        ]);
    }
}
