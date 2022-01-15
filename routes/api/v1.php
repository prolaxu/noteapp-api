<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\NoteBookController;

Route::post('register',[ApiUserController::class,'registerUser']);
Route::post('login',[ApiUserController::class,'loginUser']);

Route::middleware('auth:api')->group(function(){
    Route::get('user', [ApiUserController::class,'authenticatedUserDetails']);
    Route::resource('notes', NoteController::class);
    Route::resource('notebooks', NoteBookController::class);
});