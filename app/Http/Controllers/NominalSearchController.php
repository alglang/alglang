<?php

namespace App\Http\Controllers;

use App\NominalSearch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NominalSearchController extends Controller
{
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
