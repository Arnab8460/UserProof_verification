<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProofController;

Route::get('/', function () {
    return view('welcome');
});
// Route to display the user proof verification page
Route::get('/users', [UserProofController::class, 'index']);
// Route to approve a proof
Route::post('/approve/{id}/{type}', [UserProofController::class, 'approve']);
// Route to reject a proof
Route::post('/reject/{id}/{type}', [UserProofController::class, 'reject']);
// Route to handle proof reupload
Route::post('/reupload/{id}', [UserProofController::class, 'reupload']);
// Route to handle proof filter
Route::post('/filter-users', [UserProofController::class, 'filterUsers']);
// Route to handle loadmore button with pagiantion
Route::get('/load-more-users', [UserProofController::class, 'loadMoreUsers']);

