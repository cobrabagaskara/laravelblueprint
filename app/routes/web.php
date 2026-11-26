<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PollController;
use Illuminate\Support\Facades\Route;

// Halaman Utama & Detail Berita
Route::get('/', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/category/{category:slug}', [NewsController::class, 'byCategory'])->name('news.category');
Route::get('/tag/{tag:slug}', [NewsController::class, 'byTag'])->name('news.tag');

// Kirim Komentar (Guest maupun User Login)
Route::post('/news/{news:slug}/comments', [CommentController::class, 'store'])->name('comments.store');

// Kirim Vote Polling
Route::post('/polls/{poll}/vote', [PollController::class, 'vote'])->name('polls.vote');
