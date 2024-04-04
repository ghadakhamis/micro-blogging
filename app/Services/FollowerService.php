<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\FollowerRepository;
use App\Models\Follower;
use Illuminate\Support\Facades\Auth;
class FollowerService extends BaseService
{
    public function __construct(FollowerRepository $repository)
    {
        $this->setRepository($repository);
    }

    public function create(User $user): Follower
    {
        $data = ['user_id' => Auth::id(), 'following_id' => $user->id];
        return $this->repository->firstOrCreate($data, $data);
    }

    public function unFollow(User $following): void
    {
        $this->repository->unFollow(Auth::id(), $following);
    }
}
