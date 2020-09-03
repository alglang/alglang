<?php

namespace App\Http\Controllers;

use App\Search\SmartSearch;
use Illuminate\Http\RedirectResponse;

class SmartSearchController extends Controller
{
    public function index(): RedirectResponse
    {
        $q = request()->q;
        $url = SmartSearch::urlFor($q);

        if (!$url) {
            return back()->withErrors([
                'q' => "Sorry, we couldn't find a match for \"{$q}\"!"
            ]);
        }

        return redirect($url);
    }
}
