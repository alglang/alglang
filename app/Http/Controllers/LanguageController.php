<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show', 'index');
        $this->middleware('permission:create languages')->except('show', 'index');
    }

    public function index()
    {
        return ['data' => Language::all()];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Language  $language
     * @return \Illuminate\View\View
     */
    public function show(Language $language)
    {
        $language->load('group', 'children', 'parent');
        return view('languages.show', ['language' => $language]);
    }

    public function create()
    {
        return view('languages.create');
    }

    public function store()
    {
        $languageData = request()->validate([
            'name' => 'required|string|unique:App\Language',
            'algo_code' => 'required|string|max:5',
            'group_id' => 'required|exists:App\Group,id',
            'parent_id' => 'required|exists:App\Language,id',
            'reconstructed' => 'boolean',
            'position' => 'json',
            'notes' => 'string'
        ]);

        $language = Language::create($languageData);
        $language->load('parent', 'group');
        return $language;
    }
}
