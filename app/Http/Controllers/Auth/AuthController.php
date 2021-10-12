<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\AuthRegisterResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    public function __construct(
        private UserService $userService,
    ){}

    public function register(AuthRegisterRequest $request)
    {
        try {
            $user = $this->userService->register(
                $request->get('name'),
                $request->get('email'),
                $request->get('password'),
            );
        } catch (Throwable $t) {
            return response()->json([
                'message' => $t->getMessage(),
            ], 500);
        }

        return response()->json(new AuthRegisterResource($user));
    }

    public function login(Request $request)
    {

    }
}
