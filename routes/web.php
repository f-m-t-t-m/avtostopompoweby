<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ConfirmUserController;
use App\Http\Controllers\DeparmentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
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

// route от Ксюхи
Route::get('/notifications', function () {
    return view('notifications');
})->name('notifications');

Route::match(['get', 'post'], '/login', LoginController::class)->name('login');
Route::match(['get', 'post'], '/register', RegisterController::class)->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/logout', LogoutController::class)->name('logout');
    Route::get('/', [HomePageController::class, 'show'])->name('main_page');
    Route::get('/discipline/{id}', [SubjectController::class, 'show'])->name('discipline');
    Route::get('/section/{id}', [SectionController::class, 'show'])->name('section');
    Route::post('/sections/store', [SectionController::class, 'store'])->name('store_section');
    Route::post('comments/store', [CommentController::class, 'store'])->name('store_comment');

    Route::get('/create-group/{id}', [GroupController::class, 'show_create_form'])->name('create-group');
    Route::post('/create-group/save', [GroupController::class, 'create_group'])->name('save_group');

    Route::get('/create_disciplines/{id}', [SubjectController::class, 'show_create_form'])->name('create-disciplines');
    Route::post('/create_disciplines/save', [SubjectController::class, 'create_subject'])->name('save_subject');

    Route::get('/confirm-student-registration', [ConfirmUserController::class, 'show'])
        ->name('confirm-student-registration');
    Route::patch('/confirm_user/{id}', [ConfirmUserController::class, 'confirm'])
        ->name('confirm_user');

    Route::post('/create-department/save', [DeparmentController::class, 'create'])
        ->name('save_department');
    Route::post('/change-department', [DeparmentController::class, 'change_department'])
        ->name('change-department');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
});

Route::get('/comments/{id}/pagination', [SectionController::class, "get_comments"]);

//Route::get('/test/confirm-student-registration', function () {
//    return view('confirm-student-registration');
//})->name('confirm-student-registration');
//Route::get('/test/discipline', function () {
//    return view('discipline');
//})->name('discipline');
//Route::get('/test/study-section', function () {
//    return view('study-section');
//})->name('study-section');
//Route::get('/test/home-head', function () {
//    return view('home-head');
//})->name('home-head');
//Route::get('/test/home-admin', function () {
//    return view('home-admin');
//})->name('home-admin');
//Route::get('/test/create-group', function () {
//    return view('create-group');
//})->name('create-group');
//Route::get('/test/user-profile', function () {
//    return view('user-profile');
//})->name('user-profile');

