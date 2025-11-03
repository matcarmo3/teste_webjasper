<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/teste', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::apiResource('products', ProductController::class);
