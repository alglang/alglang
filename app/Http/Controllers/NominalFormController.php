<?php

namespace App\Http\Controllers;

use App\Language;
use App\NominalForm;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NominalFormController extends Controller
{
    public function show(Language $language, NominalForm $nominalForm): View
    {
        $nominalForm->loadCount('examples');
        return view('nominal-forms.show', ['form' => $nominalForm]);
    }
}
