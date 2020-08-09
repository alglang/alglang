<?php

namespace App\Http\Controllers;

use App\Example;
use App\VerbForm;
use App\Language;
use App\Source;
use App\Http\Resources\ExampleCollection;
use Illuminate\Database\Eloquent\Builder;
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
        if (!request()->form_id && !request()->source_id) {
            abort(400);
        }

        $query = Example::query();

        if (request()->form_id) {
            $query->whereHas('form', function (Builder $query) {
                return $query->where('id', request()->form_id);
            });
        }
       
        if (request()->source_id) {
            $query->whereHas('sources', function (Builder $query) {
                return $query->where('sources.id', request()->source_id);
            });
        }

        $paginator = $query->with('form')
                          ->paginate(10)
                          ->appends([
                              'form_id' => request()->form_id
                          ]);

        return new ExampleCollection($paginator);
    }
}
