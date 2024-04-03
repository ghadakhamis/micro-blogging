<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());
        return response()->json(['user' => new UserResource($user), 'token' => $user->generateToken()], Response::HTTP_OK);
    }

    public function login(LoginRequest $request)
    {
        $user = $this->service->login($request->validated());
        
        if ($user) {
            return response()->json(['user' => new UserResource($user), 'token' => $user->generateToken()], Response::HTTP_OK);
        }

        return response()->json(['message' => trans('auth.failed'), 'errors' => ['email' => trans('auth.failed')]], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
