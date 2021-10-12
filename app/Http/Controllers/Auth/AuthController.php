<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Resources\AuthLoginResource;
use App\Http\Resources\AuthRegisterResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(new AuthRegisterResource($user));
    }

    public function login(AuthLoginRequest $request)
    {
        $token = $this->userService->getToken(
            $request->get('email'),
            $request->get('password'),
        );

        if (!$token) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND]
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(new AuthLoginResource($token));
    }
}
