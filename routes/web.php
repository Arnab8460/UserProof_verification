<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProofController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/users', [UserProofController::class, 'index']);
Route::post('/approve/{id}/{type}', [UserProofController::class, 'approve']);
Route::post('/reject/{id}/{type}', [UserProofController::class, 'reject']);
Route::post('/reupload/{id}', [UserProofController::class, 'reupload']);
Route::post('/filter-users', [UserProofController::class, 'filterUsers']);
Route::get('/load-more-users', [UserProofController::class, 'loadMoreUsers']);

