<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, News $news)
    {
        abort_unless($news->is_published, 404);

        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:100'],
            'author_email' => ['required', 'email', 'max:150'],
            'content' => ['required', 'string', 'min:3', 'max:1000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        $comment = $news->comments()->create([
            'user_id' => auth()->id(),
            'author_name' => $validated['author_name'],
            'author_email' => $validated['author_email'],
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
            'status' => 'pending', // Moderasi admin di Filament
        ]);

        return response()->json([
            'message' => 'Komentar berhasil dikirim dan menunggu moderasi.',
            'comment' => $comment,
        ], 201);
    }
}
