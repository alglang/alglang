<?php

namespace App\Http\Controllers;

use App\Language;

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
}
