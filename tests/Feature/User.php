<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class User extends TestCase
{
    public function testRegister()
    {
        $response = $this->json('POST', '/api/auth/register', [
            'email'  =>  'test@example.com',
            'password'  =>  '123456',
        ]);

        $response->assertStatus(201);

        // Receive bearer token
        $this->assertArrayHasKey('bearerToken', $response->json());
    }

    public function testLogin()
    {
        $response = $this->json('POST', '/api/auth/login', [
            'email'  =>  'test@test.com',
            'password'  =>  'test',
        ]);

        $response->assertStatus(200);

        // Receive bearer token
        $this->assertArrayHasKey('bearerToken', $response->json());
    }
}
