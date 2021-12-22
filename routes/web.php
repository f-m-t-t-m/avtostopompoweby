<?php

use App\Http\Controllers\HomePageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubjectController;
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
    Route::get('/', [HomePageController::class, 'show'])->name('main_page');
    Route::get('/discipline/{id}', [SubjectController::class, 'show'])->name('discipline');
    Route::get('/section/{id}', [SectionController::class, 'show'])->name('section');
    Route::post('/sections/store', [SectionController::class, 'store'])->name('store_section');
});

Route::get('/section', function () {
    return view('study-section');
});
