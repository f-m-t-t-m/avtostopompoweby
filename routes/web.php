<?php

use App\Http\Controllers\HomePageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', [HomePageController::class, 'show'])->name('main_page');

Route::get('/', function () {
    return view('discipline');
})->name('main_page');

Route::get('/create-disciplines', function () {
    return view('create-disciplines');
})->name('create-disciplines');

Route::get('/create-group', function () {
    return view('create-group');
})->name('create-group');

Route::match(['get', 'post'], '/login', LoginController::class)->name('login');
Route::match(['get', 'post'], '/register', RegisterController::class)->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/logout', LogoutController::class)->name('logout');
});

Route::get('/test/home-student', function () {
    return view('home-student');
});

Route::get('/test/home-teacher', function () {
    return view('home-teacher');
});

Route::get('/test/home-head', function () {
    return view('home-head');
});
