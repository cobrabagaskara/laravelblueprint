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

        $validated = $request->validate([
            'poll_option_id' => ['required', 'exists:poll_options,id'],
        ]);

        $option = PollOption::where('id', $validated['poll_option_id'])
            ->where('poll_id', $poll->id)
            ->firstOrFail();

        DB::transaction(function () use ($request, $poll, $option) {
            $poll->pollVotes()->create([
                'poll_option_id' => $option->id,
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
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
