<?php

namespace App\Http\Controllers;

use App\Language;
use App\VerbForm;
use App\Http\Resources\VerbFormCollection;
use Illuminate\Http\Request;

class VerbFormController extends Controller
{
    public function index(Language $language): VerbFormCollection
    {
        return VerbFormCollection::fromLanguage($language);
    }

    public function show(Language $language, VerbForm $verbForm): \Illuminate\View\View
    {
        return view('verb-forms.show', ['verbForm' => $verbForm]);
    }
}
