<?php

namespace App\Http\Controllers;

use App\VerbSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class VerbSearchController extends Controller
{
    public function forms(): View
    {
        $results = VerbSearch::search(request()->all());

        return view('search.verbs.forms', ['results' => $results]);
    }
}
