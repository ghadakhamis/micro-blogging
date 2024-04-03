<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * AuthController for regist and login user
 */
class AuthController extends Controller
{
    /**
     * @param AuthService $service
     */
    public function __construct(protected AuthService $service)
    {
    }

    /**
     * Register user
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->service->register($request->validated());
        return response()->json(['user' => new UserResource($user), 'token' => $user->generateToken()], Response::HTTP_OK);
    }

    /**
     * Login user
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->service->login($request->validated());
        
        return $user? 
            response()->json(['user' => new UserResource($user), 'token' => $user->generateToken()], Response::HTTP_OK)
            : response()->json(['message' => trans('auth.failed'), 'errors' => ['email' => trans('auth.failed')]], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
