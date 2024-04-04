<?php

namespace App\Repositories;

use App\Models\Follower;
use App\Models\User;

class FollowerRepository extends BaseRepository
{
    public function __construct(Follower $model)
    {
        parent::__construct($model);
    }

    public function unFollow(int $userId, User $following): void
    {
        $this->model->where('user_id', $userId)
            ->where('following_id', $following->id)
            ->first()
            ->delete();
    }
}