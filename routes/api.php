<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use App\Http\Resources\UserResource;
use App\Models\User;
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

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user()->load('role');
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('user', UserController::class);
    // Route::get('/user', function (Request $request) {
    //     return $request->user();
    // });
});

Route::post('/login/admin', [AuthController::class, 'adminLogin']);

Route::post('/register', [RegisterController::class, 'store']);
Route::get('/account/verify/{token}', [UserController::class, 'verifyAccount'])->name('user.verify');
Route::post('/activate-account', [UserController::class, 'activateAccount']);
Route::get('/users', [UserController::class, 'getUser'])->name('user.all');

