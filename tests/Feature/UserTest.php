<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class UserTest extends TestCase
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

    public function test_users_can_be_sorted_by_total_post_views()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Post::factory()->create(['user_id' => $user1->id, 'views_count' => 10]);
        Post::factory()->create(['user_id' => $user2->id, 'views_count' => 20]);

        $token1 = $this->getTokenForUser($user1);
        $token2 = $this->getTokenForUser($user2);

        $response = $this->getJson('/api/v1/users', [
            'Authorization' => 'Bearer ' . $token1
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'username', 'mobile', 'avatar', 'total_posts_views'
                         ]
                     ]
                 ]);

        $responseData = $response->json()['data'];
        $this->assertGreaterThan($responseData[1]['total_posts_views'], $responseData[0]['total_posts_views']);
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