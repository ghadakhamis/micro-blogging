<?php

namespace App\Observers;
use App\Models\Follower;

class FollowerObserver
{
    /**
     * Handle the Follower "created" event.
     */
    public function created(Follower $follower): void
    {
        $follower->user()->increment('following_count');
        $follower->following()->increment('followers_count');
    }

    /**
     * Handle the Follower "deleted" event.
     */
    public function deleted(Follower $follower): void
    {
        $follower->user()->decrement('following_count');
        $follower->following()->decrement('followers_count');
    }
}
