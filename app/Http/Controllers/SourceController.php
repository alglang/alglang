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
        if (request()->language_id) {
            /** @var Language */
            $language = Language::find(request()->language_id);
            $query = $language->sources();
        } else {
            $query = Source::query();
        }

        $sources = $query->paginate(10);
        return new SourceCollection($sources);
    }
    
    public function show(Source $source): View
    {
        $source->loadCount('examples', 'morphemes', 'verbForms');
        return view('sources.show', ['source' => $source]);
    }
}
