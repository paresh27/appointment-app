<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepositoryInterface;

class AuthController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepoistory)
    {
        $this->userRepository = $userRepoistory;
    }

    /**
     * Handle user registration.
     *
     * @return UserResource
     */
    public function register(RegistrationRequest $request)
    {
        $user = $this->userRepository->create($request->all());

        return (new UserResource($user))->additional([
            'status' => 'success',
            'message' => 'User registered successfully',
        ]);
    }

    /**
     * Handle user login and return authentication token.
     *
     * @return mixed|UserResource|\Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {

        $user = $this->userRepository->findByEmail($request->email);

        if (! $user) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        return (new UserResource($user))->additional([
            'status' => 'success',
            'message' => 'User login successfully',
            'token' => $this->userRepository->createToken($user),
        ]);

    }

    /**
     * Logout the authenticated user by revoking tokens.
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }
}
