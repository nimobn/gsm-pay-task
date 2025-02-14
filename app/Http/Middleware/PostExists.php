<?php

namespace App\Http\Middleware;

use App\Models\PostView;
use App\Repositories\PostRepository;
use Closure;
use Illuminate\Http\Request;


class PostExists
{
    public function __construct(
        private PostRepository $postRepository,
    ) {}
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route('id');
        $post = $this->postRepository->findPost($id);

        if (!$post) {
            return response()->json([
                'message' => 'The requested post was not found.'
            ], 404);
        }

        return $next($request);
    }
}