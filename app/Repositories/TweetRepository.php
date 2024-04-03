<?php

namespace App\Repositories;

use App\Models\Tweet;

class TweetRepository extends BaseRepository
{
    public function __construct(Tweet $model)
    {
        parent::__construct($model);
    }
}