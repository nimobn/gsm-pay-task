<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $clientRepository = app(ClientRepository::class);
        $clientRepository->createPersonalAccessClient(
            null, 'Personal Access Client', 'http://localhost'
        );
    }

    public function test_user_can_login_and_receive_jwt_token()
    {
        User::factory()->create([
            'username' => 'test',
            'mobile' => '09123456789',            
            'password' => Hash::make('123456'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'username' => 'test',
            'password' => '123456',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                            'username',
                            'mobile',
                            'avatar',
                         ],
                         'access_token',
                         'server_time'
                 ]);

        $this->assertArrayHasKey('access_token', $response->json());
    }
}