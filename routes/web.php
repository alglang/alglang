<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\GlossController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MorphemeController;
use App\Http\Controllers\NominalFormController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\VerbFormController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/groups/{group:slug}', [GroupController::class, 'show'])->name('groups.show');
Route::post('/groups', [GroupController::class, 'create']);

Route::get('/languages/create', [LanguageController::class, 'create'])->name('languages.create');
Route::get('/languages/{language:slug}', [LanguageController::class, 'show'])->name('languages.show');

Route::get(
    '/languages/{language:slug}/morphemes/{morpheme:slug}',
    [MorphemeController::class, 'show']
)->name('morphemes.show');

Route::get(
    '/languages/{language:slug}/verb-forms/{verbForm:slug}',
    [VerbFormController::class, 'show']
)->name('verb-forms.show');

Route::get(
    '/languages/{language:slug}/nominal-forms/{nominalForm:slug}',
    [NominalFormController::class, 'show']
);

Route::get(
    '/languages/{language:slug}/verb-forms/{verbForm:slug}/examples/{example:slug}',
    [ExampleController::class, 'show']
);

Route::get('/glosses/{gloss}', [GlossController::class, 'show'])->name('glosses.show');

Route::get('/slots/{slot}', [SlotController::class, 'show'])->name('slots.show');

Route::get('/sources', [SourceController::class, 'index']);
Route::get('/bibliography', [SourceController::class, 'index'])->name('bibliography');
Route::get('/sources/{source:slug}', [SourceController::class, 'show']);

Route::view('/about', 'about')->name('about');
Route::view('/verb-forms', 'verb-forms.index')->name('verb-forms');

Route::get('/resources', function () {
    abort(404);
})->name('resources');

Route::get('/structural-survey', function () {
    abort(404);
})->name('structural-survey');

Route::get('/nominal-forms', function () {
    abort(404);
})->name('nominal-forms');

Route::get('/phonology', function () {
    abort(404);
})->name('phonology');

Route::get('/search/verbs/paradigm', function () {
    abort(404);
})->name('search.verbs.paradigm');

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::get('/auth/{provider}', [LoginController::class, 'redirectToProvider'])->name('auth');
Route::get('/auth/{provider}/callback', [LoginController::class, 'handleProviderCallback']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

if (app('env') === 'testing') {
    Route::get('/500', function () {
        abort(500);
    });
}
