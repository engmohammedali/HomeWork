<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvitationController;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Route::get('/cre',  [ProjectController::class,'create']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// tasks
Route::get('tasks', [TaskController::class, 'index']);

Route::get('task/{id}', [TaskController::class, 'index2']);

Route::post('task/create', [TaskController::class, 'create']);

Route::post('task/delete/{id}', [TaskController::class, 'destroy']);

Route::post('task/update/{id}', [TaskController::class, 'update']);




// user
Route::post('/user/register', [AuthController::class, 'register']);

Route::post('/user/login', [AuthController::class, 'login']);

Route::get('/users', [AuthController::class, 'index']);

Route::post('/user/{id}', [AuthController::class, 'get_user']);

Route::post('/user/delete/{id}', [AuthController::class, 'destroy']);

Route::post('/user/update/{id}', [AuthController::class, 'update']);

Route::get('/user/token/{id}', function ($id) {
    $user = User::findOrFail($id);
    if ($user->login_token) {
        return $user->login_token;
    } else {
        return "user hasnot a token";
    }
});

// Project

Route::get('/projects', [ProjectController::class, 'index']);

Route::post('/project/{id}', [ProjectController::class, 'get_project']);

Route::get('/project/add', [ProjectController::class, 'create']);

Route::post('/project/delete/{id}', [ProjectController::class, 'destroy']);

Route::post('/project/update/{id}', [ProjectController::class, 'update']);

Route::post('/projects/{project}/users/{user}', [ProjectController::class, 'addUser']);

// ////
Route::post('/sendEmail', [InvitationController::class, 'Send_invitations']);
