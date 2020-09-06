<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExampleCollection;
use App\Models\Example;
use App\Models\Form;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class ExampleController extends Controller
{
    public function show(Language $language, Form $form, Example $example): View
    {
        $example->load('form');
        $example->with('morphemes');
        return view('examples.show', ['example' => $example]);
    }
}
