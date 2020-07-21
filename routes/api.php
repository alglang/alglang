<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MorphemeController;
use App\Http\Controllers\VerbFormController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/groups', [GroupController::class, 'index']);

Route::get('/languages', [LanguageController::class, 'index']);
Route::post('/languages', [LanguageController::class, 'store']);

Route::get('/languages/{language:slug}/morphemes', [MorphemeController::class, 'index']);
Route::get('/languages/{language:slug}/verb-forms', [VerbFormController::class, 'index']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
