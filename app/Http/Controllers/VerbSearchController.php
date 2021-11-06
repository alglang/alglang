<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerbFormSearchRequest;
use App\Models\Feature;
use App\Models\Language;
use App\Models\VerbClass;
use App\Models\VerbMode;
use App\Models\VerbOrder;
use App\Models\VerbStructure;
use App\Search\VerbSearch;
use Illuminate\View\View;

class VerbSearchController extends Controller
{
    public function forms(): View
    {
        $languages = Language::orderBy('name')->get();
        $classes = VerbClass::orderBy('abv')->get();
        $modes = VerbMode::orderBy('name')->get();
        $orders = VerbOrder::orderBy('name')->get();
        $features = Feature::orderBy('name')->get();

        return view('search.verbs.forms', [
            'languages' => $languages,
            'classes' => $classes,
            'modes' => $modes,
            'orders' => $orders,
            'features' => $features
        ]);
    }

    public function paradigms(): View
    {
        $languages = Language::all();
        $classes = VerbClass::all();
        $orders = VerbOrder::all();

        return view('search.verbs.paradigms', [
            'languages' => $languages,
            'classes' => $classes,
            'orders' => $orders
        ]);
    }

    public function formResults(VerbFormSearchRequest $request): View
    {
        $columns = [];

        foreach ($request['structures'] as $structure) {
            if (isset($request['languages'])) {
                $structure['languages'] = $request['languages'];
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

        return view('search.verbs.form-results', compact('columns', 'languages'));
    }

    public function paradigmResults(): View
    {
        $validated = request()->validate([
            'languages' => 'array',
            'orders' => 'array',
            'classes' => 'array',
            'modes' => 'array',
            'negative' => 'nullable',
            'diminutive' => 'nullable'
        ]);

        $results = VerbSearch::search($validated);

        return view('search.verbs.paradigm-results', [
            'results' => $results
        ]);
    }
}
