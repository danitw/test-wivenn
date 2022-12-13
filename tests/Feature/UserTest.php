<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Create user test.
     *
     * @return void
     */
    public function test_create_user(): void
    {
        $user  = $this->createUser();
        $token = $user->createToken('API TOKEN')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer  $token")->postJson('/api/user', ['username' => 'fake', 'email' => 'fake@fake.com', 'password' => '123456789']);

        $response->assertCreated();
        $response->assertJson(['status' => true, 'message' => 'User Created Successfully']);

        $this->assertDatabaseHas('users', ['email' => 'fake@fake.com']);

        $userToken = $response->json('token');

        $createdUser = $this->withHeader('Authorization', "Bearer  $userToken")->getJson('/api/me')->assertOk();
    }

    /**
     * Read user test.
     *
     * @return void
     */
    public function test_read_user(): void
    {
        $user  = $this->createUser();
        $token = $user->createToken('API TOKEN')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer  $token")->getJson("/api/user/$user->id");

        $response->assertOk();
        $response->assertJson(['status' => true, 'message' => 'User Found Successfully', 'user' => $user->getVisible()]);
    }

    /**
     * Update user test.
     *
     * @return void
     */
    public function test_update_user(): void
    {
        $user  = $this->createUser();
        $token = $user->createToken('API TOKEN')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->putJson("/api/user/$user->id", ['username' => 'danitw', 'email' => 'danitw@danitw.com', 'password' => '999999999']);

        $response->assertOk();
        $response->assertJson(['status' => true, 'message' => 'User Updated Successfully']);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'username' => 'danitw', 'email' => 'danitw@danitw.com']);
        $this->assertDatabaseMissing('users', ['username' => 'teste', 'email' => 'teste@teste.com']);
    }

    /**
     * Delete user test.
     *
     * @return void
     */
    public function test_delete_user(): void
    {
        $user  = $this->createUser();
        $token = $user->createToken('API TOKEN')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->deleteJson("/api/user/$user->id");

        $response->assertOk();
        $response->assertJson(['status' => true, 'message' => 'User Deleted Successfully']);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
