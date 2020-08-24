<?php

namespace App\Http\Controllers;

use App\Language;
use App\Source;
use App\Http\Resources\SourceCollection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SourceController extends Controller
{
    public function index(): View
    {
        return view('sources.index');
    }

    public function fetch(): SourceCollection
    {
        if (request()->language) {
            /** @var Language */
            $language = Language::find(request()->language);
            $query = $language->sources();
        } else {
            $query = Source::query();
        }

        $sources = $query->paginate(request()->per_page ?? 10)
                         ->appends(request()->query());
        return new SourceCollection($sources);
    }
    
    public function show(Source $source): View
    {
        $source->loadCount(
            'examples',
            'morphemes',
            'verbForms',
            'nominalForms',
            'nominalParadigms'
        );
        return view('sources.show', ['source' => $source]);
    }
}
