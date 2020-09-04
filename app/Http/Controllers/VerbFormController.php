<?php

namespace App\Http\Controllers;

use App\Http\Resources\VerbFormCollection;
use App\Models\Language;
use App\Models\VerbForm;

class VerbFormController extends Controller
{
    public function fetch(): VerbFormCollection
    {
        if (!request()->language && !request()->source_id && !request()->with_morphemes) {
            abort(400);
        }

        $query = VerbForm::query();

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
            'structure',
            'structure.subject',
            'structure.primaryObject',
            'structure.secondaryObject',
            'structure.mode',
            'structure.order',
            'structure.class'
        )->paginate(request()->per_page ?? 10)
            ->appends(request()->query());

        return new VerbFormCollection($paginator);
    }

    public function show(Language $language, VerbForm $verbForm): \Illuminate\View\View
    {
        $verbForm->load(
            'structure',
            'structure.subject',
            'structure.primaryObject',
            'structure.secondaryObject',
            'structure.mode',
            'structure.order',
            'structure.class',
            'sources'
        );
        $verbForm->loadCount('examples');
        return view('verb-forms.show', ['form' => $verbForm]);
    }
}
