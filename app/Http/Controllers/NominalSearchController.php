<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchNominalParadigm;
use App\Models\Language;
use App\Models\NominalParadigmType;
use App\Search\NominalSearch;
use Illuminate\View\View;

class NominalSearchController extends Controller
{
    public function paradigms(): View
    {
        $languages = Language::orderBy('name')->get();
        $paradigmTypes = NominalParadigmType::orderBy('name')->get();

        return view('search.nominals.paradigms', [
            'languages' => $languages,
            'paradigmTypes' => $paradigmTypes
        ]);
    }

    public function paradigmResults(SearchNominalParadigm $request): View
    {
        $results = NominalSearch::search($request->validated());

        return view('search.nominals.paradigm-results', ['results' => $results]);
    }
}
