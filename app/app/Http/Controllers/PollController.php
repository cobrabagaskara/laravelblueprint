<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    public function vote(Request $request, Poll $poll)
    {
        abort_unless($poll->is_active, 403, 'Polling sudah ditutup.');

        if (($poll->starts_at && now()->lt($poll->starts_at)) || ($poll->ends_at && now()->gt($poll->ends_at))) {
            abort(403, 'Polling di luar jadwal aktif.');
        }

        $validated = $request->validate([
            'poll_option_id' => ['required', 'exists:poll_options,id'],
        ]);

        $userId = $request->user()?->id;
        $ipAddress = $request->ip() ?? '127.0.0.1';

        $voteQuery = $poll->pollVotes();
        if ($userId !== null) {
            $voteQuery->where('user_id', $userId);
        } else {
            $voteQuery->where('ip_address', $ipAddress);
        }

        if ($voteQuery->exists()) {
            return response()->json([
                'message' => 'Anda sudah memberikan suara pada polling ini.',
            ], 422);
        }

        $option = PollOption::where('id', $validated['poll_option_id'])
            ->where('poll_id', $poll->id)
            ->firstOrFail();

        DB::transaction(function () use ($request, $poll, $option, $userId, $ipAddress) {
            $poll->pollVotes()->create([
                'poll_option_id' => $option->id,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'user_agent' => $request->userAgent(),
            ]);

            $option->increment('vote_count');
            $poll->increment('total_votes');
        });

        return response()->json([
            'message' => 'Terima kasih, suara Anda telah tercatat.',
        ], 201);
    }
}
