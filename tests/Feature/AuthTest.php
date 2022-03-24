<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_can_register()
    {
        $user = [
            "first_name" => "admin",
            "last_name" => "admin",
            "email" => "admin@example.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];
        
        $this->json('POST', 'api/register', $user, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "status",
                "message",
                "data" => [
                    'token'
                ]
            ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            "email" => "admin@example.com",
            "password" => bcrypt("password")
        ]);

        $credentials = [
            "email" => $user->email,
            "password" => "password"
        ];

        $this->json('POST', 'api/login', $credentials, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                "data" => [
                    'token'
                ]
            ]);

        $this->assertAuthenticated();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->json('GET', 'api/logout', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertSee('You have been logged out successfully');
    }
}
