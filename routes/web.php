<?php

use App\Http\Controllers\GetATreatmentController;
use App\Http\Controllers\GlossaryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ContactUsFormController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TestimonialController;
use App\Models\Organizer;
use App\Models\Teacher;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Spatie\Honeypot\ProtectAgainstSpam;

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

Route::get('/',[ HomeController::class, 'index'])->name('home');



Route::group(['middleware' => ['auth:sanctum', 'verified']], function() {

    Route::name('dashboard.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('index');
    });
});



// Posts
Route::name('posts.')->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('index');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('update');
    Route::get('/posts/create', [PostController::class, 'create'])->name('create');
    Route::post('/posts', [PostController::class, 'store'])->name('store');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('destroy');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('show');

    Route::get('/blog', [PostController::class, 'blog'])->name('blog');
});

// Tags
Route::name('tags.')->group(function () {
    Route::get('/tags', [TagController::class, 'index'])->name('index');
    Route::get('/tags/{id}/edit', [TagController::class, 'edit'])->name('edit');
    Route::put('/tags/{id}', [TagController::class, 'update'])->name('update');
    Route::get('/tags/create', [TagController::class, 'create'])->name('create');
    Route::post('/tags', [TagController::class, 'store'])->name('store');
    Route::delete('/tags/{id}', [TagController::class, 'destroy'])->name('destroy');
    Route::get('/tags/{id}', [TagController::class, 'show'])->name('show');
});

// Glossaries
Route::name('glossaries.')->group(function () {
    Route::get('/glossaries', [GlossaryController::class, 'index'])->name('index');
    Route::get('/glossaries/{id}/edit', [GlossaryController::class, 'edit'])->name('edit');
    Route::put('/glossaries/{id}', [GlossaryController::class, 'update'])->name('update');
    Route::get('/glossaries/create', [GlossaryController::class, 'create'])->name('create');
    Route::post('/glossaries', [GlossaryController::class, 'store'])->name('store');
    Route::delete('/glossaries/{id}', [GlossaryController::class, 'destroy'])->name('destroy');
    Route::get('/glossaries/{id}', [GlossaryController::class, 'show'])->name('show');
});

// Teachers
Route::name('teachers.')->group(function () {
    Route::get('/teachers', [Teacher::class, 'index'])->name('index');
    Route::get('/teachers/{id}/edit', [Teacher::class, 'edit'])->name('edit');
    Route::put('/teachers/{id}', [Teacher::class, 'update'])->name('update');
    Route::get('/teachers/create', [Teacher::class, 'create'])->name('create');
    Route::post('/teachers', [Teacher::class, 'store'])->name('store');
    Route::delete('/teachers/{id}', [Teacher::class, 'destroy'])->name('destroy');
    Route::get('/teachers/{id}', [Teacher::class, 'show'])->name('show');
});

// Organizers
Route::name('organizers.')->group(function () {
    Route::get('/organizers', [Organizer::class, 'index'])->name('index');
    Route::get('/organizers/{id}/edit', [Organizer::class, 'edit'])->name('edit');
    Route::put('/organizers/{id}', [Organizer::class, 'update'])->name('update');
    Route::get('/organizers/create', [Organizer::class, 'create'])->name('create');
    Route::post('/organizers', [Organizer::class, 'store'])->name('store');
    Route::delete('/organizers/{id}', [Organizer::class, 'destroy'])->name('destroy');
    Route::get('/organizers/{id}', [Organizer::class, 'show'])->name('show');
});

// Events
Route::name('events.')->group(function () {
    Route::get('/events', [Event::class, 'index'])->name('index');
    Route::get('/events/{id}/edit', [Event::class, 'edit'])->name('edit');
    Route::put('/events/{id}', [Event::class, 'update'])->name('update');
    Route::get('/events/create', [Event::class, 'create'])->name('create');
    Route::post('/events', [Event::class, 'store'])->name('store');
    Route::delete('/events/{id}', [Event::class, 'destroy'])->name('destroy');
    Route::get('/events/{id}', [Event::class, 'show'])->name('show');
});





// Posts categories
Route::resource('postCategories', PostCategoryController::class);

// Post Comments
Route::name('postComments.')->group(function () {
    Route::post('/postComment', [PostCommentController::class, 'store'])->name('store')->middleware(ProtectAgainstSpam::class);;
});


//Route::get('tag/{tagId}',[ TagController::class, 'show'])->name('tags.show');
Route::get('glossaryTerms/{glossaryTermId}',[ GlossaryController::class, 'show'])->name('glossary.show');

// Contact form
Route::get('/contact', [ContactUsFormController::class, 'index']);
Route::post('/contact', [ContactUsFormController::class, 'ContactUsForm'])->name('contact.store');

// Be a testimonial form
Route::name('testimonials.')->group(function () {
    Route::get('/testimonial', [TestimonialController::class, 'create'])->name('create');
    Route::post('/testimonial', [TestimonialController::class, 'store'])->name('store');
});

// Get a treatment form
Route::get('/getATreatment', [GetATreatmentController::class, 'show']);
Route::post('/getATreatment', [GetATreatmentController::class, 'store'])->name('contact.store');


// Pages
//Route::get('/aboutMe', return view('pages.aboutMe'));
//return view('posts.index');

Route::get('/aboutMe', function () {
    return view('pages.aboutMe');
});
