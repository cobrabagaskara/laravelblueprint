<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Tag;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with(['category', 'user', 'tags'])
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(10);

        return response()->json($news);
    }

    public function show(News $news)
    {
        abort_unless($news->is_published, 404);

        $news->increment('view_count');
        $news->load(['category', 'user', 'tags', 'liveReport', 'comments' => function ($query) {
            $query->where('status', 'approved')->whereNull('parent_id')->with('replies');
        }]);

        return response()->json($news);
    }

    public function byCategory(Category $category)
    {
        abort_unless($category->is_active, 404);

        $news = $category->news()
            ->with(['user', 'tags'])
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(10);

        return response()->json([
            'category' => $category,
            'news' => $news,
        ]);
    }

    public function byTag(Tag $tag)
    {
        $news = $tag->news()
            ->with(['category', 'user'])
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(10);

        return response()->json([
            'tag' => $tag,
            'news' => $news,
        ]);
    }
}
