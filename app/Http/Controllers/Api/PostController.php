<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    public function __construct(
        private PostService $postService,
    ) {}

    /**
     * @OA\Get(
     * path="/api/v1/users/posts",
     * operationId="getPosts",
     * tags={"Post"},
     * summary="Get list of user posts",
     * description="Retrieve a paginated list of a user posts.",
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
     * Get list of posts 
     *
     * @return AnonymousResourceCollection
    */
    public function index(): AnonymousResourceCollection
    {
        $posts = $this->postService->getUserPosts();

        return PostResource::collection($posts)->additional([
            'server_time' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/v1/users/posts/{post}",
     * operationId="showPost",
     * tags={"Post"},
     * summary="Show a single post",
     * description="Show a single post.",
     *      security={{"bearerAuth":{}}},
     * @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="The ID of the post",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
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
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\JsonContent()
     *       ),
     * )
     * 
     * Display a single post.
     *
     * @param Post $post
     * @return PostResource
     */
    public function show(int $postId): PostResource
    {
        try {
            $post = $this->postService->showPost($postId);

            return PostResource::make($post)->additional([
                'server_time' => Carbon::now()->toDateTimeString(),
            ]);
        } catch (ModelNotFoundException $th) {
            return response()->json([
                'message' => "An unexpected error happened!"
            ]);
        }
        
    }
}
