<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * Login test.
     *
     * @return void
     */
    public function test_login(): void
    {
        $this->createUser();

        $response = $this->postJson('/api/auth/login', ['email' => 'teste@teste.com', 'password' => '123456789'])->assertOk()->assertJson(['status' => true, 'message' => 'User Logged In Successfully']);

        $userToken = $response->json('token');

        $createdUser = $this->withHeader('Authorization', "Bearer  $userToken")->getJson('/api/me')->assertOk();
    }

    /**
     * Reset password test.
     *
     * @return void
     */
    public function test_reset_password(): void
    {
        $this->createUser();

        $response = $this->postJson('/api/auth/forgot-password', ['email' => 'teste@teste.com'])->assertOk()->assertJson(
            fn (AssertableJson $json) =>
            $json->has('token')
        );

        $response = $this->postJson('/api/auth/reset-password', ['token' => $response->json('token'), 'email' => 'teste@teste.com', 'password' => '999999999', 'password_confirmation' => '999999999'])->assertOk()->assertJson(['status' => 'Password modified with success']);

        $response = $this->postJson('/api/auth/login', ['email' => 'teste@teste.com', 'password' => '999999999'])->assertOk()->assertJson(['status' => true, 'message' => 'User Logged In Successfully']);
    }

    /**
     * Logout test.
     *
     * @return void
     */
    //public function test_logout()
    //{
        //$user = $this->createUser();
        //$token = $user->createToken('API TOKEN')->plainTextToken;

        //$this->withHeader('Authorization', "Bearer  $token")->getJson('/api/user')->assertOk();

        //$this->withHeader('Authorization', "Bearer  $token")->postJson('/api/auth/logout')->assertOk()->assertJson(['message' => 'Tokens Revoked']);

        //$this->withHeader('Authorization', "Bearer  $token")->getJson('/api/user')->assertUnauthorized()->assertJson(['message' => 'Unauthenticated']);
    //}
}
