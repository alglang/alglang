<?php

namespace App\Http\Controllers;

use App\Models\FormGap;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormGapController extends Controller
{
    public function show(Language $language, FormGap $gap): View
    {
        return view('gaps.show', compact('gap'));
    }
}
