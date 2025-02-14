<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class ProfileTest extends TestCase
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

    public function test_user_can_update_profile_picture()
    {
        $user = User::factory()->create([
            'username' => 'test',
            'mobile' => '09123456789',            
            'password' => Hash::make('123456'),
        ]);

        $token = $this->getTokenForUser($user);

        $response = $this->postJson('/api/v1/users/avatar', [
            'avatar' => UploadedFile::fake()->image('profile.jpg'),
        ], ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'username',
                    'mobile',
                    'avatar',
                ]
            ]);
    }

    private function getTokenForUser($user)
    {
        $response = $this->postJson('/api/v1/login', [
            'username' => $user->username,
            'password' => '123456',
        ]);

        return $response->json('access_token');
    }
}