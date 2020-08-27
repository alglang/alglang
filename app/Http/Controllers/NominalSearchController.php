<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchNominalParadigm;
use App\Language;
use App\NominalParadigmType;
use App\NominalSearch;
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
