<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
    ) {}

    /**
     * @OA\Post(
     * path="/api/v1/register",
     * operationId="register",
     * tags={"Auth"},
     * summary="Register a new user",
     * description="Register a new user.",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"username", "mobile", "password", "password_confirmation"},
     *               @OA\Property(property="username", type="text", example="nima"),
     *               @OA\Property(property="mobile", type="text", example="09123456789"),
     *               @OA\Property(property="password", type="password", example="123456"),
     *               @OA\Property(property="password_confirmation", type="password", example="123456"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful response",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent()
     *       ),
     * )
     *
     * Register a new user.
     *
     * @param UserRegisterRequest $request
     * @return UserResource
     */
    public function register(UserRegisterRequest $request): UserResource
    {
        $user = $this->authService->registerUser(
            $request->validated()
        );
   
        return UserResource::make($user)->additional([
            'server_time' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/v1/login",
     * operationId="login",
     * tags={"Auth"},
     * summary="Login user by username and password",
     * description="Login user by username and password.",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"username", "password"},
     *               @OA\Property(property="username", type="text", example=""),
     *               @OA\Property(property="password", type="password", example=""),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful response",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent()
     *       ),
     * )
     *
     * Log in a user and return the access token.
     *
     * @param UserLoginRequest $request
     * @return UserResource
    */
    public function login(UserLoginRequest $request): UserResource|JsonResponse
    {
        $credentials = [
            'username' => $request->getUsername(),
            'password' => $request->getPassword()
        ];

        try {
            $result = $this->authService->loginUser($credentials);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        return UserResource::make($result['user'])->additional([
                'access_token' => $result['accessToken'],
                'server_time' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
