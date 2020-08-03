<?php

namespace App\Http\Controllers;

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
        $sources = Source::paginate(10);
        return new SourceCollection($sources);
    }
    
    public function show(Source $source): View
    {
        $source->loadCount('morphemes', 'verbForms');
        return view('sources.show', ['source' => $source]);
    }
}
