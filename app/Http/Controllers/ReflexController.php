<?php

namespace App\Http\Controllers;

use App\Models\Reflex;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReflexController extends Controller
{
    public function show(string $languageCode, string $phonemeSlug, string $reflexSlug): View
    {
        $reflex = Reflex::whereHas(
            'phoneme',
            fn ($query) => $query->where([
                'language_code' => $languageCode,
                'slug' => $phonemeSlug
            ])
        )->whereHas(
            'reflex',
            fn ($query) => $query->where('slug', $reflexSlug)
        )->with('phoneme', 'reflex', 'phoneme.language', 'reflex.language')
         ->firstOrFail();

        return view('reflexes.show', compact('reflex'));
    }
}
