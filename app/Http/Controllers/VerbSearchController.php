<?php

namespace App\Http\Controllers;

use App\VerbSearch;
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
            'structures.*.modes' => 'required|size:1',
            'structures.*.classes' => 'required|size:1',
            'structures.*.orders' => 'required|size:1',
            'structures.*.subject_persons' => 'nullable|size:1',
            'structures.*.subject_numbers' => 'nullable|size:1',
            'structures.*.subject_obviative_codes' => 'nullable|size:1',
            'structures.*.primary_object_persons' => 'nullable|size:1',
            'structures.*.primary_object_numbers' => 'nullable|size:1',
            'structures.*.primary_object_obviative_codes' => 'nullable|size:1',
            'structures.*.secondary_object_persons' => 'nullable|size:1',
            'structures.*.secondary_object_numbers' => 'nullable|size:1',
            'structures.*.secondary_object_obviative_codes' => 'nullable|size:1',
        ]);

        $params = [];

        if (isset($validated['structures'])) {
            $params = array_merge_recursive(...$validated['structures']);
        }

        if (isset($validated['languages'])) {
            $params['languages'] = $validated['languages'];
        }

        $results = VerbSearch::search($params);

        return view('search.verbs.forms', ['results' => $results]);
    }
}
