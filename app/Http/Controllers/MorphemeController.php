<?php

namespace App\Http\Controllers;

use App\Morpheme;
use App\Language;
use App\Source;
use App\Http\Resources\MorphemeCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MorphemeController extends Controller
{
    public function index(): MorphemeCollection
    {
        if (!request()->language_id && !request()->source_id) {
            abort(400);
        }

        $query = Morpheme::query();

        if (request()->language_id) {
            $query->whereHas('language', function (Builder $query) {
                $query->where('id', request()->language_id);
            });
        }

        if (request()->source_id) {
            $query->whereHas('sources', function (Builder $query) {
                $query->where('sources.id', request()->source_id);
            });
        }

        $paginator = $query->with('slot')
                            ->paginate(10)
                            ->appends([
                                'language_id' => request()->language_id,
                                'source_id' => request()->source_id
                            ]);

        return new MorphemeCollection($paginator);
    }

    public function show(Language $language, Morpheme $morpheme): \Illuminate\View\View
    {
        $morpheme->load('slot', 'sources');
        $morpheme->append('glosses', 'disambiguator');
        return view('morphemes.show', ['morpheme' => $morpheme]);
    }
}
