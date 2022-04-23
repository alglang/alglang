<?php

namespace App\Http\Controllers;

use App\Http\Resources\SourceCollection;
use App\Models\Language;
use App\Models\Source;
use Illuminate\View\View;

class SourceController extends Controller
{
    public function index(): View
    {
        return view('sources.index');
    }

    public function show(Source $source): View
    {
        $source->loadCount(
            'examples',
            'morphemes',
            'rules'
        );
        return view('sources.show', ['source' => $source]);
    }
}
