<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class TweetTest extends TestCase
{
    /** test */
    public function test_fail_store_tweet_without_body_and_authinticate(): void
    {
        $response = $this->json('POST', route('tweets.store'), []);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** test */
    public function test_fail_store_tweet_without_body(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        $response = $this->actingAs($user, 'user')->json('POST', route('tweets.store'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['text'], 'errors');
    }

    /** test */
    public function test_fail_store_tweet_with_max_text(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        $body     = ['text' => $this->faker->asciify(str_repeat('*', 300))];
        $response = $this->actingAs($user, 'user')->json('POST', route('tweets.store'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['text'], 'errors');
    }

    /** test */
    public function test_success_store_tweet(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        $body     = ['text' => $this->faker->asciify(str_repeat('*', 130))];
        $response = $this->actingAs($user, 'user')->json('POST', route('tweets.store'), $body);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('tweets', [
            'text'    => $body['text'],
            'user_id' => $user->id,
        ]);
    }
}
