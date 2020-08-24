<?php

namespace App\Http\Controllers;

use App\Feature;
use App\Language;
use App\VerbClass;
use App\VerbMode;
use App\VerbOrder;
use App\VerbSearch;
use App\VerbStructure;
use Illuminate\View\View;

class VerbSearchController extends Controller
{
    public function forms(): View
    {
        $languages = Language::all();
        $classes = VerbClass::all();
        $modes = VerbMode::all();
        $orders = VerbOrder::all();
        $features = Feature::all();

        return view('search.verbs.forms', [
            'languages' => $languages,
            'classes' => $classes,
            'modes' => $modes,
            'orders' => $orders,
            'features' => $features
        ]);
    }

    public function formResults(): View
    {
        $validated = request()->validate([
            'languages' => 'array',
            'structures' => 'required',
            'structures.*.modes' => 'required|array|size:1',
            'structures.*.classes' => 'required|array|size:1',
            'structures.*.orders' => 'required|array|size:1',
            'structures.*.subject_persons' => 'required|array|size:1',
            'structures.*.subject_numbers' => 'nullable|array|size:1',
            'structures.*.subject_obviative_codes' => 'nullable|array|size:1',
            'structures.*.primary_object_persons' => 'nullable|array|size:1',
            'structures.*.primary_object_numbers' => 'nullable|array|size:1',
            'structures.*.primary_object_obviative_codes' => 'nullable|array|size:1',
            'structures.*.secondary_object_persons' => 'nullable|array|size:1',
            'structures.*.secondary_object_numbers' => 'nullable|array|size:1',
            'structures.*.secondary_object_obviative_codes' => 'nullable|array|size:1',
        ]);

        $columns = [];

        foreach ($validated['structures'] as $structure) {
            if (isset($validated['languages'])) {
                $structure['languages'] = $validated['languages'];
            }

            $columns[] = [
                'query' => VerbStructure::fromSearchQuery($structure),
                'results' => VerbSearch::search($structure)
            ];
        }

        $languages = collect(array_map(fn ($column) => $column['results'], $columns))
            ->flatten(1)
            ->pluck('language')
            ->unique('code');

        return view('search.verbs.form-results', [
            'columns' => $columns,
            'languages' => $languages
        ]);
    }
}
