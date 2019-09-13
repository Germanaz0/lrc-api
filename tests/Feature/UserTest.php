<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class UserTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    const URL_SIGNUP = 'api/auth/signup';
    const URL_LOGIN = 'api/auth/login';
    const URL_LOGOUT = 'api/auth/logout';
    const URL_ME = 'api/auth/me';

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSignup()
    {
        // Generate an initial form with a wrong password (lenght)
        $form = [
            'name' => 'Tester',
            'email' => 'tester@tester.com',
            'password' => '1',
            'password_confirmation' => '1',
        ];

        // Test invalid length
        $response = $this->json('POST', self::URL_SIGNUP, $form);
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'password' => [],
                ],
            ]);

        // Test unmatching passwords
        $form['password'] = 'password';
        $form['password_confirmation'] = 'password-does-not-match';

        $response = $this->json('POST', self::URL_SIGNUP, $form);
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'password' => [],
                ],
            ]);

        // Test empty name
        $form['password'] = 'password';
        $form['password_confirmation'] = 'password';
        $form['name'] = '';

        $response = $this->json('POST', self::URL_SIGNUP, $form);
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name' => [],
                ],
            ]);

        // Finally register user
        $form['password'] = 'password';
        $form['password_confirmation'] = 'password';
        $form['name'] = 'Super Tester';

        $response = $this->json('POST', self::URL_SIGNUP, $form);
        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'message',
            ]);

        // Test unique emails it should fail
        $response = $this->json('POST', self::URL_SIGNUP, $form);
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email' => [],
                ],
            ]);
    }

    /**
     * Test login feature
     */
    public function testLogin()
    {
        // Creates user to login
        $user = factory(User::class)->create();

        $form = [
            'email' => $user->email,
            'password' => 'wrong-password', // Good: password
            'remember_me' => false,
        ];

        $response = $this->json('POST', self::URL_LOGIN, $form);
        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);

        // Finally login
        $form['password'] = 'password';
        $response = $this->json('POST', self::URL_LOGIN, $form);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_at',
            ]);

        // Test valid loogged in  user
        $access_token = $response->json(['access_token']);
        $headers = [
            'Authorization' => "Bearer ${access_token}",
        ];

        $response = $this->json('GET', self::URL_ME, [], $headers);
        $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
        ]);

        // Test Logout
        $access_token = $response->json(['access_token']);
        $headers = [
            'Authorization' => "Bearer ${access_token}",
        ];

        $response = $this->json('GET', self::URL_LOGOUT, [], $headers);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
