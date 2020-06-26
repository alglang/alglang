<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = Language::positioned()->get();
        return view('home', ['languages' => $languages]);
    }
}
