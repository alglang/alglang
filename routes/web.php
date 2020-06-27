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
Route::get('/languages/{language:slug}/morphemes/{morpheme:slug}', 'MorphemeController@show')->name('morphemes.show');
Route::get('/languages/{language:slug}/verb-forms/{verbForm:slug}', 'VerbFormController@show')->name('verb-forms.show');

Route::get('/about', function () {
    return '';
})->name('about');

Route::get('/resources', function () {
    return '';
})->name('resources');

Route::get('/structural-survey', function () {
    return '';
})->name('structural-survey');

Route::get('/verbs', function () {
    return '';
})->name('verbs');

Route::get('/nominals', function () {
    return '';
})->name('nominals');

Route::get('/bibliography', function () {
    return '';
})->name('bibliography');

Route::get('/search/verbs/paradigm', function () {
    return '';
})->name('search.verbs.paradigm');

Auth::routes();
