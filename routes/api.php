<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FavoriteController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//public
Route::get('getServices', [ServiceController::class, 'index']);
Route::get('subjects', [SubjectController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('getService/{id}', [ServiceController::class, 'getService']);
Route::get('getCategory/{id}', [CategoryController::class, 'getCategory']);
Route::get('getSubject/{id}', [SubjectController::class, 'getSubject']);
Route::post('search', [SearchController::class, 'search']);
Route::get('filterServices', [SearchController::class, 'filterServices']);

//auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('requestValidationKey', [AuthController::class, 'requestValidationKey']);
Route::post('resetPassword', [AuthController::class, 'resetPassword']);


Route::group(['middleware' => ['auth:sanctum', 'checkAdminStudentInstructor']], function(){
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('changePassword', [AuthController::class, 'changePassword']);
    Route::get('getUser', [UserController::class, 'getUser']);
    Route::post('updateUser', [UserController::class, 'updateUser']);
});

Route::group(['middleware' => ['auth:sanctum', 'checkStudentAdmin']], function(){
    Route::post('rateInstructor', [RatingController::class, 'store']);
    Route::get('favorites', [FavoriteController::class, 'index']);
    Route::post('addFavorite',[FavoriteController::class, 'store']);
    Route::delete('removeFavorite/{instructorId}',[FavoriteController::class, 'destroy']);
});


Route::group(['middleware' => ['auth:sanctum', 'checkAdmin']], function(){
    Route::get('users', [UserController::class, 'users']);
    Route::delete('deleteUser/{id}', [UserController::class, 'destroy']);
    Route::post('banUser/{id}', [UserController::class, 'banUser']);
    Route::post('updateRole', [UserController::class, 'updateRole']);

    Route::post('addSubject', [SubjectController::class, 'store']);
    Route::put('updateSubject', [SubjectController::class, 'update']);
    Route::delete('deleteSubject/{id}', [SubjectController::class, 'destroy']);

    Route::post('addCategory', [CategoryController::class, 'store']);
    Route::put('updateCategory', [CategoryController::class, 'update']);
    Route::delete('deleteCategory/{id}', [CategoryController::class, 'destroy']);
});

Route::group(['middleware' => ['auth:sanctum', 'checkAdminInstructor']], function(){
    Route::get('getUserServices', [ServiceController::class, 'getUserServices']);
    Route::post('addService', [ServiceController::class, 'addService']);
    Route::put('updateService', [ServiceController::class, 'updateService']);
    Route::delete('deleteService/{id}', [ServiceController::class, 'destroy']);
});