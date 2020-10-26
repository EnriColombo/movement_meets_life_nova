<?php

use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ContactUsFormController;
use App\Http\Controllers\FeedbackFormController;
use App\Http\Controllers\TagController;
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

Route::get('/', function () {
    return view('home');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('posts', PostController::class);
Route::resource('categories', PostCategoryController::class);

Route::get('tag/{tagId}',[ TagController::class, 'show'])->name('tags.show');

// Contact form
Route::get('/contact', [ContactUsFormController::class, 'index']);
Route::post('/contact', [ContactUsFormController::class, 'ContactUsForm'])->name('contact.store');

// Feedback form
Route::get('/feedback', [FeedbackFormController::class, 'index']);