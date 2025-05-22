<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $auth;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auth = new AuthService();
    }

    public function test_register_creates_user(): void
    {
        $user = $this->auth->register([
            'name' => 'Unit User',
            'email' => 'unit@example.com',
            'password' => 'secret123',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'unit@example.com']);
    }

    public function test_login_returns_token_for_valid_credentials(): void
    {
        User::factory()->create([
            'email' => 'unit@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $result = $this->auth->login([
            'email' => 'unit@example.com',
            'password' => 'secret123',
        ]);

        $this->assertArrayHasKey('access_token', $result);
        $this->assertArrayHasKey('user', $result);
    }

    public function test_login_fails_for_invalid_credentials(): void
    {
        $this->expectException(ValidationException::class);

        User::factory()->create([
            'email' => 'fail@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $this->auth->login([
            'email' => 'fail@example.com',
            'password' => 'wrongpassword',
        ]);
    }
}

