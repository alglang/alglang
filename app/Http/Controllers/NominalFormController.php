<?php

namespace App\Http\Controllers;

use App\Http\Resources\NominalFormCollection;
use App\Models\Language;
use App\Models\NominalForm;
use Illuminate\View\View;

class NominalFormController extends Controller
{
    public function fetch(): NominalFormCollection
    {
        $query = NominalForm::query();

        if (!request()->language && !request()->source_id && !request()->with_morphemes) {
            abort(400);
        }

        $query->when(
            request()->language,
            fn ($query, $language) => $query->whereHas('language', fn ($query) => $query->where('code', $language))
        );

        $query->when(
            request()->source_id,
            fn ($query, $source) => $query->whereHas('sources', fn ($query) => $query->where('sources.id', $source))
        );

        $query->when(
            request()->with_morphemes,
            function ($query, $morphemes) {
                foreach ($morphemes as $morpheme) {
                    $query->whereHas('morphemeConnections', fn ($query) => $query->where('morpheme_id', $morpheme));
                }
            }
        );

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
