<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
   /** test */
   public function test_fail_login_without_body(): void
   {
       $response = $this->json('POST', route('login'), []);

       $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
       $response->assertJsonValidationErrors(['email', 'password'], 'errors');
   }

    /** test */
    public function test_fail_login_without_email(): void
    {
        $body     = $this->getBody();
        unset($body['email']);
        $response = $this->json('POST', route('login'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email'], 'errors');
    }

    /** test */
    public function test_fail_login_with_invalid_email(): void
    {
        $body          = $this->getBody();
        $body['email'] = $this->faker->name();
        $response      = $this->json('POST', route('login'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email'], 'errors');
    }

    /** test */
    public function test_fail_login_with_not_exist_email(): void
    {
        $body     = $this->getBody();
        $response = $this->json('POST', route('login'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email'], 'errors');
    }

    /** test */
    public function test_fail_login_without_password(): void
    {
        $body     = $this->getBody();
        unset($body['password']);
        $response = $this->json('POST', route('login'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['password'], 'errors');
    }

    /** test */
    public function test_fail_login_with_wrong_password(): void
    {
        $user             = User::factory()->create();
        $body             = $this->getBody();
        $body['password'] = $this->faker->asciify('User-*****@');
        $response         = $this->json('POST', route('login'), $body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email'], 'errors');
    }

    /** test */
    public function test_success_login(): void
    {
        $password = $this->faker->asciify('User-*****@'.rand(1,9));
        /** @var User $user */
        $user     = User::factory()->create(['password' => $password]);
        $body     = ['email' => $user->email, 'password' => $password];
        $response = $this->json('POST', route('login'), $body);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['user', 'token']);
    }

    private function getBody(): array
    {
        return  [
            'email'    => $this->faker->unique()->email(),
            'password' => $this->faker->asciify('User-*****@'.rand(1,9)),
        ];
    }
}
