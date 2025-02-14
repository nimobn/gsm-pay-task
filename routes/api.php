<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\PostExists;
use App\Http\Middleware\TrackPostView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix('/v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware(['auth:api'])
        ->prefix('/users')
        ->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/avatar', [UserController::class, 'updateAvatar']);

            Route::get('/posts', [PostController::class, 'index']);
            Route::get('/posts/{id}', [PostController::class, 'show'])
                ->middleware([
                    PostExists::class,
                    TrackPostView::class,
                ]);
        });
        
});
