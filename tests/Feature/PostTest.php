<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class PostTest extends TestCase
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
    
    public function test_user_can_get_their_posts()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->count(5)->create(['user_id' => $user->id]);

        $token = $this->getTokenForUser($user);

        $response = $this->getJson('/api/v1/users/posts', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'title',
                             'description',
                             'views',
                             'author' => ['username', 'mobile', 'avatar']
                         ]
                     ]
                 ]);
    }

    public function test_user_can_get_single_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $token = $this->getTokenForUser($user);

        $response = $this->getJson('/api/v1/users/posts/' . $post->id, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data' => [
                        'title', 'description', 'views', 'author' => ['username', 'mobile', 'avatar']
                    ]
                 ]);
    }

    public function test_post_view_counter_increments()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $token = $this->getTokenForUser($user);

        $this->assertEquals(0, $post->views_count);

        $this->getJson('/api/v1/users/posts/' . $post->id, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $post->refresh();
        $this->assertEquals(1, $post->views_count);
    }

    public function test_post_view_counter_does_not_increment_for_same_ip()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $token = $this->getTokenForUser($user);

        $this->assertEquals(0, $post->views_count);

        $this->getJson('/api/v1/users/posts/' . $post->id, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $post->refresh();

        $this->assertEquals(1, $post->views_count);

        $this->getJson('/api/v1/users/posts/' . $post->id, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $post->refresh();
        $this->assertEquals(1, $post->views_count);
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