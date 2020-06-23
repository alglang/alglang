<?php

namespace App\Http\Controllers;

use App\Language;
use App\VerbForm;
use Illuminate\Http\Request;

class VerbFormController extends Controller
{
    public function show(Language $language, VerbForm $verbForm)
    {
        return view('verb-forms.show', ['verbForm' => $verbForm]);
    }
}
