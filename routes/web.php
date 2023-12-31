<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/* API for fetch all comments */
Route::get('/comments', [CommentsController::class, 'index']);

/* API for fetch searched comments using query */
Route::get('/comments/search', [CommentsController::class, 'search']);