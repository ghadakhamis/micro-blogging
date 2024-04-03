<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());
        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken('auth_token')->plainTextToken
        ], Response::HTTP_OK);
    }
}
