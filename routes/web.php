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

Route::get('/groups/{group:slug}', 'GroupController@show')->name('groups.show');
Route::get('/languages/{language:slug}', 'LanguageController@show')->name('languages.show');
Route::get('/languages/{language:slug}/morphemes/{morpheme:slug}', 'MorphemeController@show')->name('morphemes.show');

Auth::routes();
