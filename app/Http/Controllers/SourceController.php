<?php

namespace App\Http\Controllers;

use App\Source;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    /** @test */
    public function show(Source $source)
    {
        return view('sources.show', ['source' => $source]);
    }
}
