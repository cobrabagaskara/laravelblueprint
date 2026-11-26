<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\News;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPortalControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_only_published_news(): void
    {
        $publishedNews = News::factory()->create(['is_published' => true, 'title' => 'Berita Tayang']);
        $draftNews = News::factory()->create(['is_published' => false, 'title' => 'Berita Draft']);

        $response = $this->getJson(route('news.index'));

        $response->assertOk()
            ->assertJsonFragment(['title' => $publishedNews->title])
            ->assertJsonMissing(['title' => $draftNews->title]);
    }

    public function test_news_detail_increments_view_count_and_blocks_draft(): void
    {
        $publishedNews = News::factory()->create(['is_published' => true, 'view_count' => 10]);
        $draftNews = News::factory()->create(['is_published' => false]);

        $this->getJson(route('news.show', $publishedNews->slug))
            ->assertOk()
            ->assertJsonFragment(['title' => $publishedNews->title]);

        $this->assertDatabaseHas('news', [
            'id' => $publishedNews->id,
            'view_count' => 11,
        ]);

        $this->getJson(route('news.show', $draftNews->slug))->assertNotFound();
    }

    public function test_can_filter_news_by_category_and_tag(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        $tag = Tag::factory()->create();

        $news = News::factory()->create([
            'category_id' => $category->id,
            'is_published' => true,
        ]);
        $news->tags()->attach($tag);

        $this->getJson(route('news.category', $category->slug))
            ->assertOk()
            ->assertJsonFragment(['title' => $news->title]);

        $this->getJson(route('news.tag', $tag->slug))
            ->assertOk()
            ->assertJsonFragment(['title' => $news->title]);
    }

    public function test_visitor_can_post_comment_and_reply(): void
    {
        $news = News::factory()->create(['is_published' => true]);

        // 1. Kirim komentar utama
        $this->postJson(route('comments.store', $news->slug), [
            'author_name' => 'Budi Santoso',
            'author_email' => 'budi@example.com',
            'content' => 'Artikel yang sangat bermanfaat!',
        ])->assertCreated();

        $parentComment = Comment::first();
        $this->assertDatabaseHas('comments', [
            'news_id' => $news->id,
            'author_name' => 'Budi Santoso',
            'status' => 'pending',
        ]);

        // 2. Kirim balasan komentar
        $this->postJson(route('comments.store', $news->slug), [
            'author_name' => 'Siti Aminah',
            'author_email' => 'siti@example.com',
            'content' => 'Setuju dengan pendapat Mas Budi.',
            'parent_id' => $parentComment->id,
        ])->assertCreated();

        $this->assertDatabaseHas('comments', [
            'parent_id' => $parentComment->id,
            'author_name' => 'Siti Aminah',
        ]);
    }

    public function test_visitor_can_vote_on_active_poll(): void
    {
        $poll = Poll::factory()->create(['is_active' => true, 'total_votes' => 0]);
        $option = PollOption::factory()->create(['poll_id' => $poll->id, 'vote_count' => 0]);

        $this->postJson(route('polls.vote', $poll), [
            'poll_option_id' => $option->id,
        ])->assertCreated();

        $this->assertDatabaseHas('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $option->id,
        ]);
        $this->assertDatabaseHas('poll_options', [
            'id' => $option->id,
            'vote_count' => 1,
        ]);
        $this->assertDatabaseHas('polls', [
            'id' => $poll->id,
            'total_votes' => 1,
        ]);
    }
}
