<?php

namespace App\Http\Middleware;

use App\Models\PostView;
use App\Repositories\PostRepository;
use Closure;
use Illuminate\Http\Request;


class TrackPostView
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
        $ip = $request->ip();
        $id = $request->route('id');
        $post = $this->postRepository->findPost($id);
        
        $exists = PostView::where('post_id', $post->id)
                          ->where('ip_address', $ip)
                          ->exists();

        if (!$exists) {
            PostView::create([
                'post_id' => $post->id,
                'ip_address' => $ip,
            ]);

            $post->increment('views_count');
        }

        return $next($request);
    }
}