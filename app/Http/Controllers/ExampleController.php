<?php

namespace App\Http\Controllers;

use App\Example;
use App\VerbForm;
use App\Language;
use App\Http\Resources\ExampleCollection;
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

    public function fetch(): ExampleCollection
    {
        /** @var VerbForm */
        $form = VerbForm::find(request()->form_id);
        $paginator = $form->examples()
                          ->with('form')
                          ->paginate(10)
                          ->appends([
                              'form_id' => request()->form_id
                          ]);

        return new ExampleCollection($paginator);
    }
}
