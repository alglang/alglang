<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show', 'fetch');
        $this->middleware('permission:create languages')->except('show', 'fetch');
    }

    public function fetch(): array
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
        $language->loadCount([
            'morphemes' => function (Builder $query) {
                $query->withoutPlaceholders();
            },
            'verbForms',
            'nominalForms',
            'nominalParadigms'
        ]);
        $language->loadSourcesCount();
        return view('languages.show', ['language' => $language]);
    }

    public function create(): \Illuminate\View\View
    {
        return view('languages.create');
    }

    public function store(): Language
    {
        $languageData = request()->validate([
            'name' => 'required|string|unique:App\Language',
            'code' => 'required|string|max:5',
            'group_id' => 'required|exists:App\Group,id',
            'parent_code' => 'required|exists:App\Language,code',
            'reconstructed' => 'boolean',
            'position' => 'json',
            'notes' => 'string'
        ]);

        $language = Language::create($languageData);
        $language->load('parent', 'group');
        return $language;
    }
}
