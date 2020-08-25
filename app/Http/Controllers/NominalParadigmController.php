<?php

namespace App\Http\Controllers;

use App\Language;
use App\NominalParadigm;
use Illuminate\View\View;

class NominalParadigmController extends Controller
{
    public function show(Language $language, NominalParadigm $nominalParadigm): View
    {
        $nominalParadigm->load([
            'language',
            'type',
            'forms' => function ($query) {
                return $query->orderByFeatures();
            }
        ]);
        return view('nominal-paradigms.show', ['paradigm' => $nominalParadigm]);
    }
}
