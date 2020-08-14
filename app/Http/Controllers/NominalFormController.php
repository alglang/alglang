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

        if (!request()->language_id && !request()->source_id && !request()->with_morphemes) {
            abort(400);
        }

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

        if (request()->with_morphemes) {
            foreach (request()->with_morphemes as $morpheme) {
                $query->where(function (Builder $query) use ($morpheme) {
                    $query->where('morpheme_structure', $morpheme)
                          ->orWhere('morpheme_structure', 'LIKE', "$morpheme-%")
                          ->orWhere('morpheme_structure', 'LIKE', "%-$morpheme")
                          ->orWhere('morpheme_structure', 'LIKE', "%-$morpheme-%");
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
