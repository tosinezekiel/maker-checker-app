<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Document;
use App\Notifications\ReviewNotification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DocumentTest extends TestCase
{
    use DatabaseMigrations;

    const APPROVAL_ERROR_MESSAGE = 'You are not allowed to approve a request made by you.';
    const APPROVAL_SUCCESS_MESSAGE = 'This request has been approved successfully.';

    public function test_that_unauthenticated_user_cannot_make_request()
    {
        $document = Document::factory()->make();
        $this->json('POST', 'api/documents', $document->toArray(), ['Accept' => 'application/json'])
            ->assertStatus(401);
    }

    public function test_that_authenticated_user_can_make_create_request()
    {
        $userDocument = User::factory()->make();
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->json('POST', 'api/documents', $userDocument->toArray(), ['Accept' => 'application/json'])
            ->assertStatus(201);
        $response->assertJsonFragment(["type" => "Create"]);
    }

    public function test_that_authenticated_user_can_make_update_request()
    {
            $userDocument = User::factory()->make();
            $user = User::factory()->create();
            $profile = Profile::factory()->create();
            $response = $this->actingAs($user)
                ->json('PUT', "api/documents/{$profile->id}", $userDocument->toArray(), ['Accept' => 'application/json'])
                ->assertStatus(200);
            $response->assertJsonFragment(["type" => "Update"]);
    }

    public function test_that_authenticated_user_can_make_delete_request()
    {
            $userDocument = User::factory()->make();
            $user = User::factory()->create();
            $profile = Profile::factory()->create();
            $response = $this->actingAs($user)
                ->json('GET', "api/documents/{$profile->id}/delete", ['Accept' => 'application/json'])
                ->assertStatus(200);
            $response->assertJsonFragment(["type" => "Delete"]);
    }

    public function test_that_authenticated_user_can_view_a_pending_request()
    {
        $userDocument = User::factory()->make();
        $document = Document::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->json('GET', "api/documents/{$document->uuid}/", ['Accept' => 'application/json'])
            ->assertStatus(200);
        $response->assertSee($document->author->email);
    }

    public function test_that_request_author_cannot_approve_self_request()
    {
            $author = User::factory()->create();
            $userDocument = Document::factory()->create([
                "author_id" => $author->id
            ]);
            $response = $this->actingAs($author)
                ->json('GET', "api/documents/{$userDocument->id}/approve", ['Accept' => 'application/json'])
                ->assertStatus(403);
            $response->assertSee(self::APPROVAL_ERROR_MESSAGE);
    }

    public function test_that_permitted_user_can_approve_request()
    {
            $userDocument = Document::factory()->create();
            $author = User::factory()->create();
            $response = $this->actingAs($author)
                ->json('GET', "api/documents/{$userDocument->id}/approve", ['Accept' => 'application/json'])
                ->assertStatus(200);
            $response->assertSee(self::APPROVAL_SUCCESS_MESSAGE);
    }

    public function test_that_other_admin_gets_an_email_when_a_request_is_made()
    {
        Notification::fake();
        $author = User::factory()->create();
        $otherAdmin = User::factory()->create();
        $document = User::factory()->create();

        $this->actingAs($author)
            ->json('POST', 'api/documents', $document->toArray(), ['Accept' => 'application/json'])
            ->assertStatus(201);

        Notification::assertNotSentTo(
            [$author], ReviewNotification::class
        ); 

        Notification::assertSentTo(
            [$otherAdmin], ReviewNotification::class
        );      
    }
}
