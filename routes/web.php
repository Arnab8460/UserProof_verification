<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProofController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/users', [UserProofController::class, 'index']);
Route::post('/approve/{id}', [UserProofController::class, 'approve']);
Route::post('/reject/{id}', [UserProofController::class, 'reject']);
Route::post('/reupload/{id}', [UserProofController::class, 'reupload']);
