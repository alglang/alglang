<?php

namespace App\Http\Controllers;

use App\Language;
use App\Source;
use App\VerbForm;
use App\Http\Resources\VerbFormCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VerbFormController extends Controller
{
    public function index(): VerbFormCollection
    {
        if (!request()->language_id && !request()->source_id) {
            abort(400);
        }

        $query = VerbForm::query();

        if (request()->language_id) {
            $query->whereHas('language', function (Builder $query) {
                return $query->where('id', request()->language_id);
            });
        }

        if (request()->source_id) {
            $query->whereHas('sources', function (Builder $query) {
                return $query->where('sources.id', request()->source_id);
            });
        }

        $paginator = $query->with('subject', 'primaryObject', 'secondaryObject', 'mode', 'order', 'class')
              ->paginate(10)
              ->appends([
                  'language_id' => request()->language_id,
                  'source_id' => request()->source_id
              ]);

        return new VerbFormCollection($paginator);
    }

    public function show(Language $language, VerbForm $verbForm): \Illuminate\View\View
    {
        $verbForm->load('sources');
        return view('verb-forms.show', ['verbForm' => $verbForm]);
    }
}
