<?php

namespace App\Http\Controllers;

use App\Http\Resources\MorphemeCollection;
use App\Models\Language;
use App\Models\Morpheme;
use Illuminate\Database\Eloquent\Builder;

class MorphemeController extends Controller
{
    public function show(Language $language, Morpheme $morpheme): \Illuminate\View\View
    {
        $morpheme->load('slot', 'sources');
        $morpheme->loadVerbFormsCount();
        $morpheme->loadNominalFormsCount();
        $morpheme->append('glosses', 'disambiguator');
        return view('morphemes.show', ['morpheme' => $morpheme]);
    }
}
