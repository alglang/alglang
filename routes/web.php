<?php

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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/groups/{group:slug}', 'GroupController@show')->name('groups.show');
Route::post('/groups', 'GroupController@create');
Route::get('/languages/{language:slug}', 'LanguageController@show')->name('languages.show');
Route::get('/languages/{language:slug}/morphemes', 'MorphemeController@index');
Route::get('/languages/{language:slug}/morphemes/{morpheme:slug}', 'MorphemeController@show')->name('morphemes.show');
Route::get('/languages/{language:slug}/verb-forms/{verbForm:slug}', 'VerbFormController@show')->name('verb-forms.show');
Route::get('/glosses/{gloss}', 'GlossController@show')->name('glosses.show');
Route::get('/slots/{slot}', 'SlotController@show')->name('slots.show');

Route::view('/about', 'about')->name('about');

Route::get('/resources', function () {
    abort(404);
})->name('resources');

Route::get('/structural-survey', function () {
    abort(404);
})->name('structural-survey');

Route::get('/verbs', function () {
    abort(404);
})->name('verbs');

Route::get('/nominals', function () {
    abort(404);
})->name('nominals');

Route::get('/bibliography', function () {
    abort(404);
})->name('bibliography');

Route::get('/search/verbs/paradigm', function () {
    abort(404);
})->name('search.verbs.paradigm');

Auth::routes();
