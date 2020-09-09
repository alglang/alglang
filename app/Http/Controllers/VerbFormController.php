<?php

namespace App\Http\Controllers;

use App\Http\Resources\VerbFormCollection;
use App\Models\Language;
use App\Models\VerbForm;

class VerbFormController extends Controller
{
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
