<?php

namespace App\Http\Controllers;

use App\Gloss;
use Illuminate\Http\Request;

class GlossController extends Controller
{
    public function show(Gloss $gloss)
    {
        return view('glosses.show', ['gloss' => $gloss]);
    }
}
