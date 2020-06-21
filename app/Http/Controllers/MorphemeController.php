<?php

namespace App\Http\Controllers;

use App\Morpheme;
use App\Language;
use Illuminate\Http\Request;

class MorphemeController extends Controller
{
    public function show(Language $language, Morpheme $morpheme)
    {
        return view('morphemes.show', ['morpheme' => $morpheme]);
    }
}
