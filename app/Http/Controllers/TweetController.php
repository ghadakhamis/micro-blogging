<?php

namespace App\Http\Controllers;

use App\Services\TweetService;
use App\Http\Requests\StoreTweetRequest;
use App\Http\Resources\TweetResource;

class TweetController extends Controller
{
    public function __construct(protected TweetService $service)
    {
    }

    /**
     * Store new tweet
     * @param StoreTweetRequest $request
     * @return TweetResource
     */
    public function store(StoreTweetRequest $request): TweetResource
    {
        $tweet = $this->service->create($request->validated());
        return new TweetResource($tweet);
    }
}
