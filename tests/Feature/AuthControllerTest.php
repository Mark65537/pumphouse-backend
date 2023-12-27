<?php

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use WithFaker;
    
    public function testLoginAdmin()
    {

        $response = $this->postJson('api/login', [
            'name' => 'admin',
            'password' => 'admin',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user',
                     'token'
                 ]);
    }

    public function testLoginWithMissingName()
    {
        $response = $this->postJson('api/login', [
            'password' => 'somePassword',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    public function testLoginWithMissingPassword()
    {
        $response = $this->postJson('api/login', [
            'name' => 'JohnDoe',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }
    public function testInvalidCredentials()
    {
        $response = $this->postJson('/api/login', [
            'name' => 'John',
            'password' => 'wrongpassword',
        ]);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name'])
                 ->assertJson([
                     'message' => 'The provided credentials are incorrect.',
                 ]);
    }   
    
}