<?php

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->json('GET', 'api/bills');
        $response->assertStatus(200);
    }
}