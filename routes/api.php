<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiCommentController;
use App\Http\Controllers\ApiGroupController;
use App\Http\Controllers\ApiSectionController;
use App\Http\Controllers\ApiSubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    Route::apiResource('subjects', ApiSubjectController::class)
        ->scoped([
            'subject' => 'id'
        ])
        ->missing(function () {
            return response()->json(['message' => 'Subject not found'], 404);
        });
    Route::apiResource('subjects.sections', ApiSectionController::class)
        ->scoped([
            'subject' => 'id',
            'section' => 'id'
        ])
        ->missing(function () {
            return response()->json(['message' => 'Section not found'], 404);
        });

    Route::apiResource('subjects.sections.comments', ApiCommentController::class)
        ->scoped([
            'subject' => 'id',
            'section' => 'id',
            'comment' => 'id'
        ])
        ->missing(function () {
            return response()->json(['message' => 'Comment not found'], 404);
        });
});

Route::apiResource('groups', ApiGroupController::class);



