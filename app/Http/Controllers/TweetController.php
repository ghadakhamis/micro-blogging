<?php

namespace App\Http\Controllers;

use App\Filters\TweetFilters;
use App\Http\Requests\FilterTweetRequest;
use App\Services\TweetService;
use App\Http\Requests\StoreTweetRequest;
use App\Http\Resources\TweetResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function index(FilterTweetRequest $request, TweetFilters $filters): AnonymousResourceCollection
    {
        $result = $this->service->filter($filters);
        return TweetResource::collection($result);
    }
}
