<?php

use App\Http\Controllers\Api\{CastMemberController, CategoryController, GenreController};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => "I'm alive"]);
});

Route::apiResource('/categories', CategoryController::class);
Route::apiResource(
    name: '/genres',
    controller: GenreController::class
);
Route::apiResource('/cast_members', CastMemberController::class);
