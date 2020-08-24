<?php

namespace App\Http\Controllers;

use App\Language;
use App\NominalSearch;
use App\NominalParadigmType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NominalSearchController extends Controller
{
    public function paradigms(): View
    {
        $languages = Language::all();
        $paradigmTypes = NominalParadigmType::all();

        return view('search.nominals.paradigms', [
            'languages' => $languages,
            'paradigmTypes' => $paradigmTypes
        ]);
    }

    public function paradigmResults(): View
    {
        $validated = request()->validate([
            'languages' => 'required_without:paradigm_types|array',
            'paradigm_types' => 'required_without:languages|array'
        ]);

        $results = NominalSearch::search($validated);

        return view('search.nominals.paradigm-results', ['results' => $results]);
    }
}
