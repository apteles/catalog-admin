<?php

use App\Http\Controllers\Api\{
    CategoryController
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => "I'm alive"]);
});

Route::apiResource('/categories', CategoryController::class);
