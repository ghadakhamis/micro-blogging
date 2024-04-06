<?php

namespace App\Services;

use App\Filters\QueryFilters;
use App\Repositories\TweetRepository;
use App\Models\Tweet;
class TweetService extends BaseService
{
    public function __construct(TweetRepository $repository)
    {
        $this->setRepository($repository);
    }

    public function create(Array $data): Tweet
    {
        return $this->repository->create($data);
    }

    public function filter(QueryFilters $filters)
    {
        return $this->repository->filters($filters);
    }
}
