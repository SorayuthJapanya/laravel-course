<?php

use Illuminate\Support\Facades\Route;

Route::get('/healthy', function () {
    return response()->json([
        'status' => [
            'application' => true,
            'database' => true,
        ]
    ]);
});


