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
            ->paginate(request()->per_page ?? 10)
            ->appends(request()->query());

        return new ExampleCollection($paginator);
    }
}
