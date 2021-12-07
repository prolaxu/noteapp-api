<?php
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\NoteController;


Route::post('register',[ApiUserController::class,'registerUser']);
Route::post('login',[ApiUserController::class,'loginUser']);

Route::middleware('auth:api')->group(function(){
    Route::get('user', [ApiUserController::class,'authenticatedUserDetails']);
    Route::resource('notes', NoteController::class);
});