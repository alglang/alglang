<?php

namespace App\Http\Controllers;

use App\Morpheme;
use App\Language;
use Illuminate\Http\Request;

class MorphemeController extends Controller
{
    public function index(Language $language)
    {
        $morphemes = $language->morphemes()->with('slot')->paginate(10);

        $morphemes->each(function ($morpheme) {
            $morpheme->append('glosses');
        });

        return $morphemes;
    }

    public function show(Language $language, Morpheme $morpheme)
    {
        $morpheme->load('slot');
        $morpheme->append('glosses');
        return view('morphemes.show', ['morpheme' => $morpheme]);
    }
}
