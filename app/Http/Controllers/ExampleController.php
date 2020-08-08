<?php

namespace App\Http\Controllers;

use App\Example;
use App\VerbForm;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExampleController extends Controller
{
    public function show(Language $language, VerbForm $verbForm, Example $example): View
    {
        $example->load('form');
        $example->with('morphemes');
        return view('examples.show', ['example' => $example]);
    }
}
