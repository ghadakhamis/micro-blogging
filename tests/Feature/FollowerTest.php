<?php

namespace Tests\Feature;

use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class FollowerTest extends TestCase
{
    /** test */
    public function test_fail_follow_without_authinticate(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        $response = $this->json('POST', route('users.follow', ['user' => $user->id]), []);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** test */
    public function test_fail_follow_invalid_user(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        $response = $this->actingAs($user, 'user')->json('POST', route('users.follow', ['user' => 0]), []);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** test */
    public function test_fail_follow_him_self(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        $response = $this->actingAs($user, 'user')->json('POST', route('users.follow', ['user' => $user->id]), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** test */
    public function test_success_follow(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var User $authUser */
        $authUser = User::factory()->create();
        $response = $this->actingAs($authUser, 'user')->json('POST', route('users.follow', ['user' => $user->id]), []);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('followers', [
            'user_id'      => $authUser->id,
            'following_id' => $user->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id'              => $authUser->id,
            'following_count' => 1,
        ]);
        $this->assertDatabaseHas('users', [
            'id'              => $user->id,
            'followers_count' => 1,
        ]);
    }

    /** test */
    public function test_fail_un_follow_without_authinticate(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        $response = $this->json('POST', route('users.un_follow', ['user' => $user->id]), []);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** test */
    public function test_fail_un_follow_invalid_user(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        $response = $this->actingAs($user, 'user')->json('POST', route('users.un_follow', ['user' => 0]), []);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** test */
    public function test_fail_un_follow_not_following_user(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var User $authUser */
        $authUser = User::factory()->create();
        $response = $this->actingAs($authUser, 'user')->json('POST', route('users.un_follow', ['user' => $user->id]), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** test */
    public function test_success_un_follow(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var User $authUser */
        $authUser = User::factory()->create();
        Follower::factory()->create(['user_id' => $authUser->id, 'following_id' => $user->id]);
        $response = $this->actingAs($authUser, 'user')->json('POST', route('users.un_follow', ['user' => $user->id]), []);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('followers', [
            'user_id'      => $authUser->id,
            'following_id' => $user->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id'              => $authUser->id,
            'following_count' => 0,
        ]);
        $this->assertDatabaseHas('users', [
            'id'              => $user->id,
            'followers_count' => 0,
        ]);
    }
}
