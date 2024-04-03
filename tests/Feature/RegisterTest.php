<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** test */
    public function test_fail_register_without_body(): void
    {
        $response = $this->json('POST', route('register'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email', 'username', 'password'], 'errors');
    }

    /** test */
    public function test_fail_register_without_email(): void
    {
        $body     = $this->getBody();
        unset($body['email']);
        $response = $this->json('POST', route('register'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email'], 'errors');
    }

    /** test */
    public function test_fail_register_invalid_email(): void
    {
        $body          = $this->getBody();
        $body['email'] = $this->faker->name();
        $response      = $this->json('POST', route('register'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email'], 'errors');
    }

    /** test */
    public function test_fail_register_not_unique_email(): void
    {
        $user          =  User::factory()->create();
        $body          = $this->getBody();
        $body['email'] = $user->email;
        $response      = $this->json('POST', route('register'), $body);
 
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email'], 'errors');
    }

    /** test */
    public function test_fail_register_without_username(): void
    {
        $body     = $this->getBody();
        unset($body['username']);
        $response = $this->json('POST', route('register'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['username'], 'errors');
    }

    /** test */
    public function test_fail_register_username_has_spaces(): void
    {
        $body             = $this->getBody();
        $body['username'] = $this->faker->name();
        $response         = $this->json('POST', route('register'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['username'], 'errors');
    }

    /** test */
    public function test_fail_register_username_max_length(): void
    {
        $body             = $this->getBody();
        $body['username'] = $this->faker->asciify(str_repeat('*', 300));
        $response         = $this->json('POST', route('register'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['username'], 'errors');
    }

    /** test */
    public function test_fail_register_without_password(): void
    {
        $body     = $this->getBody();
        unset($body['password']);
        $response = $this->json('POST', route('register'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['password'], 'errors');
    }

    /** test */
    public function test_fail_register_password_min_length(): void
    {
        $body             = $this->getBody();
        $body['password'] = $this->faker->asciify(str_repeat('*', 7));
        $response         = $this->json('POST', route('register'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['password'], 'errors');
    }

    /** test */
    public function test_fail_register_invalid_image(): void
    {
        $body          = $this->getBody();
        $body['image'] = UploadedFile::fake()->image('test.gif');
        $response      = $this->json('POST', route('register'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['image'], 'errors');
    }

    /** test */
    public function test_fail_register_size_image(): void
    {
        $body          = $this->getBody();
        $body['image'] = UploadedFile::fake()->image('test.png')->size(rand(1000, 2000));
        $response      = $this->json('POST', route('register'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['image'], 'errors');
    }

    /** test */
    public function test_success_register_without_image(): void
    {
        $body     = $this->getBody();
        unset($body['image']);
        $response = $this->json('POST', route('register'), $body);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'user', 'token'
        ]);
        $this->assertDatabaseHas('users', [
            'email'    => $body['email'],
            'username' => $body['username'],
            'image'    => null
        ]);
    }

    /** test */
    public function test_success_register(): void
    {
        Storage::fake('public');

        $body     = $this->getBody();
        $response = $this->json('POST', route('register'), $body);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'user', 'token'
        ]);
        $this->assertDatabaseHas('users', [
            'email'    => $body['email'],
            'username' => $body['username'],
            'image'    => '/storage/profiles/'.now()->timestamp.'.png'
        ]);
        Storage::disk('public')->assertExists('/profiles/'.now()->timestamp.'.png');
    }

    private function getBody(): array
    {
        return  [
            'username' => $this->faker->firstName(),
            'email'    => $this->faker->unique()->email(),
            'password' => $this->faker->asciify('User-*****@'.rand(1,9)),
            'image'    => UploadedFile::fake()->image('test.png'),
        ];
    }
}
