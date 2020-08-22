<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\GlossController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MorphemeController;
use App\Http\Controllers\NominalFormController;
use App\Http\Controllers\NominalParadigmController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\VerbFormController;
use App\Http\Controllers\VerbParadigmController;
use App\Http\Controllers\VerbSearchController;
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

/*
|--------------------------------------------------------------------------
| Static routes
|--------------------------------------------------------------------------
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/verb-forms', 'verb-forms.index')->name('verb-forms');
Route::view('/nominal-forms', 'nominal-forms.index')->name('nominal-forms');
Route::view('/phonology', 'phonemes.index')->name('phonology');

/*
|--------------------------------------------------------------------------
| Model routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('groups')->group(function () {
    Route::get('{group:slug}', [GroupController::class, 'show'])->name('groups.show');
    Route::post('', [GroupController::class, 'create']);
});

Route::prefix('languages')->group(function () {
    Route::get('create', [LanguageController::class, 'create'])->name('languages.create');

    Route::prefix('{language:slug}')->group(function () {
        Route::get('', [LanguageController::class, 'show'])->name('languages.show');
        Route::get('nominal-forms/{nominalForm:slug}', [NominalFormController::class, 'show']);
        Route::get('nominal-paradigms/{nominalParadigm:slug}', [NominalParadigmController::class, 'show']);
        Route::get('morphemes/{morpheme:slug}', [MorphemeController::class, 'show'])->name('morphemes.show');
        Route::get('verb-forms/{verbForm:slug}', [VerbFormController::class, 'show'])->name('verb-forms.show');
        Route::get('verb-forms/{verbForm:slug}/examples/{example:slug}', [ExampleController::class, 'show']);
        Route::get('verb-paradigms', [VerbParadigmController::class, 'show'])->name('verbParadigms.show');
    });
});

Route::get('/glosses/{gloss}', [GlossController::class, 'show'])->name('glosses.show');
Route::get('/slots/{slot}', [SlotController::class, 'show'])->name('slots.show');

Route::get('/bibliography', [SourceController::class, 'index'])->name('bibliography');
Route::prefix('sources')->group(function () {
    Route::get('', [SourceController::class, 'index']);
    Route::get('{source:slug}', [SourceController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| Search routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('search')->group(function () {
    Route::prefix('verbs')->group(function () {
        Route::get('forms', [VerbSearchController::class, 'forms'])->name('search.verbs.forms');
        Route::get('forms/results', [VerbSearchController::class, 'formResults'])->name('search.verbs.form-results');
    });
});
/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
|
*/

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::get('/auth/{provider}', [LoginController::class, 'redirectToProvider'])->name('auth');
Route::get('/auth/{provider}/callback', [LoginController::class, 'handleProviderCallback']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Dead routes
|--------------------------------------------------------------------------
|
*/

Route::get('/resources', function () {
    abort(404);
})->name('resources');

Route::get('/structural-survey', function () {
    abort(404);
})->name('structural-survey');

Route::get('/search/verbs/paradigm', function () {
    abort(404);
})->name('search.verbs.paradigm');

/*
|--------------------------------------------------------------------------
| Testing routes
|--------------------------------------------------------------------------
|
*/

if (app('env') === 'testing') {
    Route::get('/500', function () {
        abort(500);
    });
}
