<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/register', function() {
    return view('register');
})->name('register.view');

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', function() {
    return view('login');
})->name('login.view');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('me', [AuthController::class, 'me'])->middleware('auth.firebase');

Route::get('/dashboard', function() {
    return view('dashboard');
})->middleware('auth.firebase')->name('dashboard');
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
