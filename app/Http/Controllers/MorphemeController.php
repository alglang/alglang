<?php

namespace App\Http\Controllers;

use App\Http\Resources\MorphemeCollection;
use App\Models\Language;
use App\Models\Morpheme;
use Illuminate\Database\Eloquent\Builder;

class MorphemeController extends Controller
{
    public function fetch(): MorphemeCollection
    {
        if (!request()->language && !request()->source_id) {
            abort(400);
        }

        $query = Morpheme::withoutPlaceholders();

        if (request()->language) {
            $query->whereHas('language', function (Builder $query) {
                $query->where('code', request()->language);
            });
        }

        if (request()->source_id) {
            $query->whereHas('sources', function (Builder $query) {
                $query->where('sources.id', request()->source_id);
            });
        }

        $paginator = $query->with('slot')
            /* ->orderByRaw('trim(shape, \'-\')') */
            ->paginate(request()->per_page ?? 50)
            ->appends(request()->query());

        return new MorphemeCollection($paginator);
    }

    public function show(Language $language, Morpheme $morpheme): \Illuminate\View\View
    {
        $morpheme->load('slot', 'sources');
        $morpheme->loadVerbFormsCount();
        $morpheme->loadNominalFormsCount();
        $morpheme->append('glosses', 'disambiguator');
        return view('morphemes.show', ['morpheme' => $morpheme]);
    }
}
