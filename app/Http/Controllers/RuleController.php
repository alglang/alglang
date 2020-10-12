<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RuleController extends Controller
{
    public function show(Language $language, Rule $rule): View
    {
        return view('rules.show', compact('rule'));
    }
}
