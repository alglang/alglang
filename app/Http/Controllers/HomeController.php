<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $languages = Language::positioned()->get();
        return view('home', ['languages' => $languages]);
    }

    public function search(): View
    {
        $languages = Language::orderBy('name')->get();
        return view('search.index', ['languages' => $languages]);
    }
}
