<?php

namespace App\Http\Controllers;

use App\Morpheme;
use App\Language;
use App\Http\Resources\MorphemeCollection;
use Illuminate\Http\Request;

class MorphemeController extends Controller
{
    public function index(Language $language): MorphemeCollection
    {
        return new MorphemeCollection($language->morphemes()->with('slot')->paginate(10));
    }

    public function show(Language $language, Morpheme $morpheme): \Illuminate\View\View
    {
        $morpheme->load('slot');
        $morpheme->append('glosses', 'disambiguator');
        return view('morphemes.show', ['morpheme' => $morpheme]);
    }
}
