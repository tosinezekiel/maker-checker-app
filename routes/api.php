<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\DocumentsController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\ApprovalsController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum', 'return-json']], function () {
    Route::get('logout', [LoginController::class, 'logout']);
    Route::put('documents/{profile}', [DocumentsController::class, 'update']);
    Route::get('documents', [DocumentsController::class, 'index']);
    Route::get('documents/{document:uuid}', [DocumentsController::class, 'show']);
    Route::post('documents', [DocumentsController::class, 'create']);
    Route::get('documents/{profile}/delete', [DocumentsController::class, 'delete']);

    Route::middleware('can.approve.request')->group(function () {
        Route::get('documents/{document}/approve', [ApprovalsController::class, 'approve']);
        Route::get('documents/{document}/decline', [ApprovalsController::class, 'decline']);
    });
    
});


