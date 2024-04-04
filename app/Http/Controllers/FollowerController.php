<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowUserRequest;
use App\Http\Requests\UnFollowUserRequest;
use App\Http\Resources\FollowerResource;
use App\Models\User;
use App\Services\FollowerService;
use Illuminate\Http\Response;

class FollowerController extends Controller
{
    public function __construct(protected FollowerService $service)
    {
    }

    public function follow(FollowUserRequest $request, User $user): FollowerResource
    {
        $follower = $this->service->create($user);
        return new FollowerResource($follower);
    }

    public function unFollow(UnFollowUserRequest $request, User $user)
    {
        $this->service->unFollow($user);
        return response()->json(['message' => trans('messages.un_follow')], Response::HTTP_OK);
    }
}
