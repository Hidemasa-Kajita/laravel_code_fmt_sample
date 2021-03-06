<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Resources\AuthRegisterResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

/**
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{
    /**
     * @param UserService $userService
     */
    public function __construct(
        private UserService $userService,
    ){}

    /**
     * ユーザー登録
     *
     * @param AuthRegisterRequest $request
     * @return JsonResponse
     */
    public function register(AuthRegisterRequest $request): JsonResponse
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

    /**
     * ログイン
     *
     * @param AuthLoginRequest $request
     * @return JsonResponse
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $token = $this->userService->issueToken(
            $request->get('email'),
            $request->get('password'),
        );

        if (!$token) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND]
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'access_token' => $token
        ]);
    }
}
