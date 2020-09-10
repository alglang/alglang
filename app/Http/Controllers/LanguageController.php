<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

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

    public function show(Language $language): View
    {
        $language->load('group', 'children', 'parent');
        $language->loadCount([
            'morphemes' => function (Builder $query) {
                $query->withoutPlaceholders();
            },
            'verbForms',
            'nominalForms',
            'nominalParadigms',
            'phonemes'
        ]);
        $language->loadSourcesCount();
        return view('languages.show', ['language' => $language]);
    }

    public function create(): View
    {
        return view('languages.create');
    }

    public function store(): Language
    {
        $languageData = request()->validate([
            'name' => ['required', 'string', 'unique:' . Language::class],
            'code' => 'required|string|max:5',
            'group_name' => ['required', 'exists:' . Group::class . ',name'],
            'parent_code' => ['required', 'exists:' . Language::class . ',code'],
            'reconstructed' => 'boolean',
            'position' => 'json',
            'notes' => 'string'
        ]);

        if (isset($languageData['position'])) {
            $languageData['position'] = json_decode($languageData['position']);
        }

        $language = Language::create($languageData);
        $language->load('parent', 'group');
        return $language;
    }
}
