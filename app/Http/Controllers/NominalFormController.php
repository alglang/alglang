<?php

namespace App\Http\Controllers;

use App\Http\Resources\NominalFormCollection;
use App\Models\Language;
use App\Models\NominalForm;
use Illuminate\View\View;

class NominalFormController extends Controller
{
    public function show(Language $language, NominalForm $nominalForm): View
    {
        $nominalForm->loadCount('examples');
        return view('nominal-forms.show', ['form' => $nominalForm]);
    }
}
