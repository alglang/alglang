<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\FormGapController;
use App\Http\Controllers\GlossController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MorphemeController;
use App\Http\Controllers\NominalFormController;
use App\Http\Controllers\NominalParadigmController;
use App\Http\Controllers\NominalSearchController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\SmartSearchController;
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
    Route::get('', function () {
        return redirect()->route('groups.show', ['group' => 'algonquian']);
    })->name('languages.index');

    Route::get('create', [LanguageController::class, 'create'])->name('languages.create');

    Route::prefix('{language:code}')->group(function () {
        Route::get('', [LanguageController::class, 'show'])->name('languages.show');

        Route::prefix('rules')->group(function () {
            Route::get('{rule:abv}', [RuleController::class, 'show']);
        });

        Route::prefix('morphemes')->group(function () {
            Route::get(
                '{morpheme:slug}',
                [MorphemeController::class, 'show']
            )->name('morphemes.show');
        });

        Route::prefix('nominal-forms')->group(function () {
            Route::get('gaps/{gap:id}', [FormGapController::class, 'show']);

            Route::get(
                '{nominalForm:slug}',
                [NominalFormController::class, 'show']
            )->name('nominalForms.show');
            Route::get(
                '{form:slug}/examples/{example:slug}',
                [ExampleController::class, 'show']
            )->name('nominalForms.examples.show');
        });

        Route::prefix('nominal-paradigms')->group(function () {
            Route::get(
                '{nominalParadigm:slug}',
                [NominalParadigmController::class, 'show']
            )->name('nominalParadigms.show');
        });

        Route::prefix('verb-forms')->group(function () {
            Route::get('gaps/{gap:id}', [FormGapController::class, 'show']);

            Route::get(
                '{verbForm:slug}',
                [VerbFormController::class, 'show']
            )->name('verbForms.show');
            Route::get(
                '{form:slug}/examples/{example:slug}',
                [ExampleController::class, 'show']
            )->name('verbForms.examples.show');
        });

        Route::get(
            'verb-paradigms',
            [VerbParadigmController::class, 'show']
        )->name('verbParadigms.show');
    });
});

Route::prefix('glosses')->group(function () {
    Route::get('{gloss}', [GlossController::class, 'show'])->name('glosses.show');
});

Route::prefix('slots')->group(function () {
    Route::get('{slot}', [SlotController::class, 'show'])->name('slots.show');
});

Route::get('/bibliography', [SourceController::class, 'index'])->name('bibliography');
Route::prefix('sources')->group(function () {
    Route::get('', [SourceController::class, 'index']);
    Route::get('{source:slug}', [SourceController::class, 'show'])->name('sources.show');
});

/*
|--------------------------------------------------------------------------
| Search routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('search')->group(function () {
    Route::prefix('nominals')->group(function () {
        Route::get(
            'paradigms',
            [NominalSearchController::class, 'paradigms']
        )->name('search.nominals.paradigms');
        Route::get(
            'paradigms/results',
            [NominalSearchController::class, 'paradigmResults']
        )->name('search.nominals.paradigm-results');
    });

    Route::prefix('verbs')->group(function () {
        Route::get(
            'paradigms',
            [VerbSearchController::class, 'paradigms']
        )->name('search.verbs.paradigms');
        Route::get(
            'paradigm-results',
            [VerbSearchController::class, 'paradigmResults']
        )->name('search.verbs.paradigm-results');

        Route::get(
            'forms',
            [VerbSearchController::class, 'forms']
        )->name('search.verbs.forms');
        Route::get(
            'forms/results',
            [VerbSearchController::class, 'formResults']
        )->name('search.verbs.form-results');
    });

    Route::get('smart', [SmartSearchController::class, 'index'])->name('smart-search');
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
| Testing routes
|--------------------------------------------------------------------------
|
*/

if (app('env') === 'testing') {
    Route::get('/500', function () {
        abort(500);
    });
}
