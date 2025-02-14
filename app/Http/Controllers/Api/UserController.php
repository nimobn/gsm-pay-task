<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserAvatarRequest;
use App\Http\Resources\SortedUsersResource;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {}
    /**
     * @OA\Get(
     * path="/api/v1/users",
     * operationId="getUsers",
     * tags={"User"},
     * summary="Get list of users sorted by post views",
     * description="Retrieve a paginated list of users, sorted by the total number of views on their posts.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful response",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent()
     *       ),
     * )
     *
     * Get list of users sorted by post views 
     *
     * @return AnonymousResourceCollection
    */
    public function index(): AnonymousResourceCollection
    {
        $users = $this->userService->getUsers();

        return SortedUsersResource::collection($users)->additional([
            'server_time' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * Update user avatar
     *
     * @OA\Post(
     *     path="/api/v1/users/avatar",
     *     tags={"User"},
     *     summary="Update user avatar",
     *     description="Upload a new avatar for the authenticated user.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"avatar"},
     *                 @OA\Property(
     *                     property="avatar",
     *                     type="string",
     *                     format="binary",
     *                     description="Avatar image file (jpg,jpeg,png,webp)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation",
     *     ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent()
     *       ),
     * )
     * 
     * Update the user's avatar.
     *
     * @param UpdateUserAvatarRequest $request
     * @return UserResource
     */
    public function updateAvatar(UpdateUserAvatarRequest $request): UserResource
    {
        $updatedUser = $this->userService->updateAvatar($request->getAvatar());

        return UserResource::make($updatedUser)->additional([
            'server_time' => Carbon::now()->toDateTimeString(),
        ]);
    }


}
