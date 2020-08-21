<?php

namespace App\Http\Controllers;

use App\VerbSearch;
use App\VerbStructure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class VerbSearchController extends Controller
{
    public function forms(): View
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

        $languages = collect(array_map(fn($column) => $column['results'], $columns))
            ->flatten(1)
            ->pluck('language')
            ->unique('id');

        return view('search.verbs.forms', [
            'columns' => $columns,
            'languages' => $languages
        ]);
    }
}
