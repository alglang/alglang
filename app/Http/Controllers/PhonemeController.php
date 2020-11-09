<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Phoneme;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhonemeController extends Controller
{
    public function show(Language $language, Phoneme $phonoid): View
    {
        $phonoid->load(['language', 'features', 'sources']);

        return view('phonemes.show', ['phoneme' => $phonoid]);
    }
}
