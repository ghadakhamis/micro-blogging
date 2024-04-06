<?php

namespace Tests\Feature;

use App\Models\Follower;
use App\Models\Tweet;
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

    /** test */
    public function test_fail_tweets_timeline_without_authinticate(): void
    {
        $response = $this->json('GET', route('tweets.index'), []);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** test */
    public function test_success_tweets_timeline_with_no_followings(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        $response = $this->actingAs($user, 'user')->json('GET', route('tweets.index'), []);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            ['data' => ['*' => ['id', 'text', 'endDate', 'user_id', 'created_at', 'updated_at']], 'links', 'meta']
        );
        $response->assertJsonCount(0, 'data');
    }

    /** test */
    public function test_success_tweets_timeline_with_followings(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var User $authUser */
        $authUser = User::factory()->create();

        Follower::factory()->create(['user_id' => $authUser->id, 'following_id' => $user->id]);
        Tweet::factory()->count(5)->create();
        Tweet::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($authUser, 'user')->json('GET', route('tweets.index'), []);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            ['data' => ['*' => ['id', 'text', 'user_id', 'created_at', 'updated_at']], 'links', 'meta']
        );
        $response->assertJsonCount(1, 'data');
    }

    /** test */
    public function test_success_tweets_timeline_with_query_params(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var User $authUser */
        $authUser = User::factory()->create();
 
        Follower::factory()->create(['user_id' => $authUser->id, 'following_id' => $user->id]);
        Tweet::factory()->count(5)->create();
        $tweets   = Tweet::factory()->count(2)->create(['user_id' => $user->id]);
 
        $response = $this->actingAs($authUser, 'user')->json('GET', route('tweets.index'), []);
 
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            ['data' => ['*' => ['id', 'text', 'user_id', 'created_at', 'updated_at']], 'links', 'meta']
        );
        $response->assertJsonCount(2, 'data');

        // text page number
        $response = $this->actingAs($authUser, 'user')->json('GET', route('tweets.index', ['page' => 2]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');

        // text search
        $tweets->first()->update(['text' => 'test']);
        $response = $this->actingAs($authUser, 'user')->json('GET', route('tweets.index', ['search' => 'tweet']));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');

        // text sort
        $response = $this->actingAs($authUser, 'user')->json('GET', route('tweets.index', ['sort' => 'created_at,desc']));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(2, 'data');
    }
}
