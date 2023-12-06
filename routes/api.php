<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//Route::group([ 'prefix' => 'user', ], function () {
    Route::prefix('user')->group(function(){
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
});

//Route::group([ 'prefix' => 'admin', ], function () {
    Route::prefix('admin')->group(function(){
    Route::post('register' , [AdminAuthController::class , 'register']);
    Route::post('login' , [AdminAuthController::class , 'login']);
});


Route::group(['middleware' => ['auth:api' , 'TransactionLogging']], function(){

    Route::post('logout' , [AuthController::class , 'logout']);

////////FILE && GROUP Routes/////////

    Route::post('add/file',[\App\Http\Controllers\FileController::class,'addFile']);
    Route::post('delete/file',[\App\Http\Controllers\FileController::class,'deleteFile']);
    Route::post('CreateGroup',[\App\Http\Controllers\GroupController::class,'CreateGroup']);
    Route::post('AddFileToGroup',[\App\Http\Controllers\GroupController::class,'AddFileToGroup']);
    Route::post('deleteFileFromGroup',[\App\Http\Controllers\GroupController::class,'deleteFileFromGroup']);
    Route::post('addUserToGroup',[\App\Http\Controllers\GroupController::class,'addUserToGroup']);
    Route::post('deleteUserFromGroup',[\App\Http\Controllers\GroupController::class,'deleteUserFromGroup']);
    Route::post('deleteGroup',[\App\Http\Controllers\GroupController::class,'deleteGroup']);
    Route::post('getHistory',[\App\Http\Controllers\HistoryController::class,'getHistory']);

/////////Display Routes/////////////////

Route::get('getMyFile',[\App\Http\Controllers\DisplayController::class,'getMyFile']);
Route::post('getStateFile',[\App\Http\Controllers\DisplayController::class,'getStateFile']);
Route::get('getMyGroup',[\App\Http\Controllers\DisplayController::class,'getMyGroup']);
Route::post('getGroupFile',[\App\Http\Controllers\DisplayController::class,'getGroupFile']);
Route::get('getAllUserInSystem',[\App\Http\Controllers\DisplayController::class,'getAllUserInSystem']);
Route::post('getAllUserInGroup',[\App\Http\Controllers\DisplayController::class,'getAllUserInGroup']);
Route::post('getAllFileCheck_InGroupForUser',[\App\Http\Controllers\DisplayController::class,'getAllFileCheck_InGroupForUser']);

///////// Check-in && Check-out /////////////////

Route::post('check_in',[\App\Http\Controllers\FileOperationsController::class,'check_in']);
Route::post('readFile',[\App\Http\Controllers\FileOperationsController::class,'readFile']);
Route::post('saveFile',[\App\Http\Controllers\FileOperationsController::class,'saveFile']);
Route::post('check_outFile',[\App\Http\Controllers\FileOperationsController::class,'check_outFile']);
});


///////// Admin Routes /////////////////

Route::group(['middleware'=>['App\Http\Middleware\AdminAuth:admin-api' , 'TransactionLogging']] , function(){

    Route::post('logout' , [AdminAuthController::class , 'logout']);

    Route::get('getAllFileInSystem',[\App\Http\Controllers\AdminController::class,'getAllFileInSystem']);
    Route::post('getAllFileInGroup',[\App\Http\Controllers\AdminController::class,'getAllFileInGroup']);
    Route::get('getAllGroupInSystem',[\App\Http\Controllers\AdminController::class,'getAllGroupInSystem']);
    Route::post('changeFileNumber',[\App\Http\Controllers\AdminController::class,'changeFileNumber']);
});




