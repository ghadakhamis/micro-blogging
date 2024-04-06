<?php

namespace App\Observers;

use App\Models\Tweet;
use Illuminate\Support\Facades\Auth;

class TweetObserver
{
    /**
     * Handle the Tweet "creating" event.
     */
    public function creating(Tweet $tweet): void
    {
        $tweet->user_id = Auth::id()?? $tweet->user_id;
    }
}
