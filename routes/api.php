<?php

use App\Http\Controllers\Api\{CategoryController, GenreController};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => "I'm alive"]);
});

Route::apiResource('/categories', CategoryController::class);
Route::apiResource(
    name: '/genres',
    controller: GenreController::class
);
