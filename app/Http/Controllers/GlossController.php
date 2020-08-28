<?php

namespace App\Http\Controllers;

use App\Models\Gloss;

class GlossController extends Controller
{
    public function show(Gloss $gloss): \Illuminate\View\View
    {
        return view('glosses.show', ['gloss' => $gloss]);
    }
}
