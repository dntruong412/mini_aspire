<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * test create user
     *
     * @group User
     * @return void
     */
    public function testCreateUser()
    {
        $response = $this->post(route('backend.users.create'), [
            'name'   => 'User test 2',
            'status' => 1
        ]);
        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 1
            ]);

        return $response;
    }

    /**
     * test get a user info
     *
     * @group User
     * @depends testCreateUser
     * @return void
     */
    public function testGetUser($createResponse)
    {
        $response = $this->get(route('backend.users.get_by_id', ['userId' => $createResponse->json('data.id')]));
        $response->assertStatus(200);

        $response = $this->get(route('backend.users.get_by_id', ['userId' => 0]));
        $response->assertStatus(404);
    }

    /**
     * test update user info
     *
     * @group User
     * @depends testCreateUser
     * @return void
     */
    public function testUpdateUser($createResponse)
    {
        $response = $this->post(route('backend.users.update', ['userId' => $createResponse->json('data.id')]), [
            'name'   => 'User test 1 updated',
            'status' => 1
        ]);
        $response->assertStatus(200);
    }

    /**
     * test get list of users
     *
     * @group User
     * @return void
     */
    public function testGetUsers()
    {
        $response = $this->get(route('backend.users.get'));
        $response->assertStatus(200);
    }

    /**
     * test delete a user
     *
     * @group User
     * @depends testCreateUser
     * @return void
     */
    public function testDeleteUser($createResponse)
    {
        $response = $this->delete(route('backend.users.delete', ['userId' => $createResponse->json('data.id')]));
        $response->assertStatus(200);
    }
}
