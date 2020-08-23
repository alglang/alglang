<?php

namespace App\Http\Controllers;

use App\Language;
use App\NominalForm;
use App\Http\Resources\NominalFormCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NominalFormController extends Controller
{
    public function fetch(): NominalFormCollection
    {
        $query = NominalForm::query();

        if (!request()->language && !request()->source_id && !request()->with_morphemes) {
            abort(400);
        }

        if (request()->language) {
            $query->whereHas('language', function (Builder $query) {
                return $query->where('code', request()->language);
            });
        }

        if (request()->source_id) {
            $query->whereHas('sources', function (Builder $query) {
                return $query->where('sources.id', request()->source_id);
            });
        }

        if (request()->with_morphemes) {
            foreach (request()->with_morphemes as $morpheme) {
                $query->whereHas('morphemeConnections', function (Builder $query) use ($morpheme) {
                    $query->where('morpheme_id', $morpheme);
                });
            }
        }

        $paginator = $query->with(
            'language',
            'structure',
            'structure.pronominalFeature',
            'structure.nominalFeature',
            'structure.paradigm'
        )->paginate(request()->per_page ?? 10)
         ->appends(request()->query());

        return new NominalFormCollection($paginator);
    }

    public function show(Language $language, NominalForm $nominalForm): View
    {
        $nominalForm->loadCount('examples');
        return view('nominal-forms.show', ['form' => $nominalForm]);
    }
}
