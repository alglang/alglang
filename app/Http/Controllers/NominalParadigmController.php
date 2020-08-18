<?php

namespace App\Http\Controllers;

use App\Language;
use App\NominalParadigm;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NominalParadigmController extends Controller
{
    public function show(Language $language, NominalParadigm $nominalParadigm): View
    {
        $nominalParadigm->load('language', 'type');
        return view('nominal-paradigms.show', ['paradigm' => $nominalParadigm]);
    }
}