<?php

use App\Http\Controllers\API\V1\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/healthy', function () {
    return response()->json([
        'status' => [
            'application' => true,
            'database' => true,
        ]
    ]);
});

Route::prefix('v1')->group(function () {
    Route::apiResource('posts', PostController::class);
});

