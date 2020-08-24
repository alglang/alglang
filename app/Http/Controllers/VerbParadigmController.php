<?php

namespace App\Http\Controllers;

use App\Language;
use App\VerbParadigm;
use Illuminate\View\View;

class VerbParadigmController extends Controller
{
    public function show(Language $language): View
    {
        $paradigm = new VerbParadigm([
            'language_code' => $language->code,
            'mode_name' => request()->mode,
            'class_abv' => request()->class,
            'order_name' => request()->order,
            'is_negative' => request()->negative,
            'is_diminutive' => request()->diminutive,
            'subject_name' => request()->subject,
            'primary_object_name' => request()->primary_object,
            'secondary_object_name' => request()->secondary_object
        ]);
        return view('verb-paradigms.show', ['paradigm' => $paradigm]);
    }
}
