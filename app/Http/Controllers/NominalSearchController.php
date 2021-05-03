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
        return view('search.nominals.paradigms');
    }

    public function paradigmResults(SearchNominalParadigm $request): View
    {
        $results = NominalSearch::search($request->validated());

        return view('search.nominals.paradigm-results', ['results' => $results]);
    }
}
