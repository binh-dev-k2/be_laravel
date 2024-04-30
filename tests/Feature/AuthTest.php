<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;


class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $response = $this->post('/login', [
            'email' => 'a@a.com',
            'password' => '123456',
        ]);
        $response->assertStatus(200);
        $this->assertStringContainsString('<response><data/><code>2</code></response>', $response->content());
    }
    public function test_login_fail()
    {
        $response = $this->post('/login', [
            'email' => 'a@a.com'

        ]);
        $response->assertStatus(200);
        $this->assertStringContainsString('The password field is required.', $response->content());
    }
}
