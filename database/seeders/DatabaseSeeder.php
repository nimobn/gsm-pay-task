<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $users = User::factory(10)->create();

        $users->each(function ($user) {
            Post::factory(3)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
