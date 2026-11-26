<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\LiveReport;
use App\Models\News;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\SiteSetting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsPortalModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_belongs_to_category_and_user_and_has_tags(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $tags = Tag::factory(3)->create();

        $news = News::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
        $news->tags()->attach($tags->pluck('id'));

        $this->assertTrue($news->user->is($user));
        $this->assertTrue($news->category->is($category));
        $this->assertCount(3, $news->tags);
        $this->assertTrue($category->news->contains($news));
        $this->assertTrue($user->news->contains($news));
    }

    public function test_news_has_one_live_report(): void
    {
        $news = News::factory()->create();
        $liveReport = LiveReport::factory()->create([
            'news_id' => $news->id,
        ]);

        $this->assertNotNull($news->liveReport);
        $this->assertTrue($news->liveReport->is($liveReport));
        $this->assertTrue($liveReport->news->is($news));
    }

    public function test_comment_supports_nested_parent_and_replies(): void
    {
        $news = News::factory()->create();
        $user = User::factory()->create();

        $parentComment = Comment::factory()->create([
            'news_id' => $news->id,
            'user_id' => $user->id,
            'parent_id' => null,
        ]);

        $replyComment = Comment::factory()->create([
            'news_id' => $news->id,
            'user_id' => $user->id,
            'parent_id' => $parentComment->id,
        ]);

        $this->assertCount(1, $parentComment->replies);
        $this->assertTrue($parentComment->replies->first()->is($replyComment));
        $this->assertTrue($replyComment->parent->is($parentComment));
    }

    public function test_poll_options_and_votes_relationships_work(): void
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();
        $option = PollOption::factory()->create([
            'poll_id' => $poll->id,
        ]);

        $vote = PollVote::factory()->create([
            'poll_id' => $poll->id,
            'poll_option_id' => $option->id,
            'user_id' => $user->id,
        ]);

        $this->assertCount(1, $poll->pollOptions);
        $this->assertCount(1, $poll->pollVotes);
        $this->assertCount(1, $option->pollVotes);
        $this->assertTrue($vote->poll->is($poll));
        $this->assertTrue($vote->pollOption->is($option));
        $this->assertTrue($vote->user->is($user));
    }

    public function test_news_and_comment_support_soft_deletes(): void
    {
        $news = News::factory()->create();
        $comment = Comment::factory()->create(['news_id' => $news->id]);

        $comment->delete();
        $news->delete();

        $this->assertSoftDeleted('comments', ['id' => $comment->id]);
        $this->assertSoftDeleted('news', ['id' => $news->id]);
    }

    public function test_site_setting_can_be_created_and_casted(): void
    {
        $setting = SiteSetting::factory()->create([
            'ticker_enabled' => 1,
        ]);

        $this->assertIsBool($setting->ticker_enabled);
        $this->assertTrue($setting->ticker_enabled);
        $this->assertDatabaseHas('site_settings', ['id' => $setting->id]);
    }
}
