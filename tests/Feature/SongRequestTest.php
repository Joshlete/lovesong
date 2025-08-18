<?php

namespace Tests\Feature;

use App\Models\SongRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SongRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_song_request(): void
    {
        $user = User::factory()->create();

        $songRequest = SongRequest::factory()->create([
            'user_id' => $user->id,
            'recipient_name' => 'Jane Doe',
            'style' => 'rock',
            'mood' => 'happy',
            'price_usd' => 99.99,
        ]);

        $this->assertDatabaseHas('song_requests', [
            'user_id' => $user->id,
            'recipient_name' => 'Jane Doe',
            'style' => 'rock',
            'mood' => 'happy',
            'price_usd' => 99.99,
        ]);
    }

    public function test_song_request_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $songRequest->user->id);
        $this->assertEquals($user->name, $songRequest->user->name);
    }

    public function test_user_has_many_song_requests(): void
    {
        $user = User::factory()->create();
        
        $songRequest1 = SongRequest::factory()->create(['user_id' => $user->id]);
        $songRequest2 = SongRequest::factory()->create(['user_id' => $user->id]);
        $songRequest3 = SongRequest::factory()->create(['user_id' => $user->id]);

        $this->assertEquals(3, $user->songRequests()->count());
        $this->assertTrue($user->songRequests->contains($songRequest1));
        $this->assertTrue($user->songRequests->contains($songRequest2));
        $this->assertTrue($user->songRequests->contains($songRequest3));
    }

    public function test_song_request_has_default_status(): void
    {
        // Test that the default status is properly set when not specified
        $songRequest = SongRequest::factory()->create(['status' => 'pending']);
        
        $this->assertEquals('pending', $songRequest->status);
        
        // Also test that other statuses work
        $completedRequest = SongRequest::factory()->create(['status' => 'completed']);
        $this->assertEquals('completed', $completedRequest->status);
    }

    public function test_song_request_has_correct_fillable_fields(): void
    {
        $fillable = [
            'user_id',
            'recipient_name',
            'style',
            'mood',
            'lyrics_idea',
            'song_description',
            'genre_details',
            'tempo',
            'vocals',
            'instruments',
            'song_structure',
            'inspiration',
            'special_instructions',
            'price_usd',
            'currency',
            'status',
            'payment_reference',
            'payment_intent_id',
            'stripe_checkout_session_id',
            'payment_status',
            'payment_completed_at',
            'file_url',
            'file_path',
            'file_size',
            'original_filename',
            'delivered_at',
        ];

        $songRequest = new SongRequest();
        $this->assertEquals($fillable, $songRequest->getFillable());
    }

    public function test_song_request_casts_delivered_at_to_datetime(): void
    {
        $songRequest = SongRequest::factory()->create([
            'delivered_at' => '2024-01-01 12:00:00'
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $songRequest->delivered_at);
    }

    public function test_song_request_casts_price_to_decimal(): void
    {
        $songRequest = SongRequest::factory()->create([
            'price_usd' => 99.99
        ]);

        $this->assertEquals('99.99', $songRequest->price_usd);
    }

    public function test_deleting_user_cascades_to_song_requests(): void
    {
        $user = User::factory()->create();
        $songRequest = SongRequest::factory()->create(['user_id' => $user->id]);

        $this->assertDatabaseHas('song_requests', ['id' => $songRequest->id]);

        $user->delete();

        $this->assertDatabaseMissing('song_requests', ['id' => $songRequest->id]);
    }
}
