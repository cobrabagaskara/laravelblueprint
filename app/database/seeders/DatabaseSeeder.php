<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\News;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Category::factory()->count(8)->create();
        User::factory()->admin()->create(['name' => 'Admin', 'email' => 'admin@beritakita.test']);
        User::factory()->editor()->count(2)->create();
        User::factory()->author()->count(5)->create();
        Tag::factory()->count(10)->create();
        News::factory()->published()->count(10)->create();
        News::factory()->draft()->count(2)->create();
        News::factory()->featured()->count(3)->create();
        $news = News::all();
        foreach ($news->take(5) as $n) {
            Comment::factory()->approved()->count(3)->create(['news_id' => $n->id]);
        }
    }
}
