<?php

namespace App\Http\Controllers;

use App\Source;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    /** @test */
    public function show(Source $source): \Illuminate\View\View
    {
        $source->loadCount('morphemes');
        return view('sources.show', ['source' => $source]);
    }
}
