<?php

namespace App\Http\Controllers;

use App\VerbForm;
use App\VerbStructure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerbSearchController extends Controller
{
    public function forms(): View
    {
        $query = VerbForm::query();

        if (request()->has('languages')) {
            $query->whereHas('language', function (Builder $query) {
                $query->whereIn('id', request()->languages);
            });
        }

        $query->whereHas('structure', function (Builder $query) {
            if (request()->has('modes')) {
                $query->whereHas('mode', function (Builder $query) {
                    $query->whereIn('name', request()->modes);
                });
            }

            if (request()->has('orders')) {
                $query->whereHas('order', function (Builder $query) {
                    $query->whereIn('name', request()->orders);
                });
            }

            if (request()->has('classes')) {
                $query->whereHas('class', function (Builder $query) {
                    $query->whereIn('abv', request()->classes);
                });
            }

            if (request()->has('negative')) {
                $query->where('is_negative', request()->negative);
            }

            if (request()->has('diminutive')) {
                $query->where('is_diminutive', request()->diminutive);
            }

            $this->filterFeature($query, 'subject', 'subject');
            $this->filterFeature($query, 'primary_object', 'primaryObject');
            $this->filterFeature($query, 'secondary_object', 'secondaryObject');
        });

        $results = $query->get();

        return view('search.verbs.forms', ['results' => $results]);
    }

    private function filterFeature(Builder $query, string $key, string $relation): void
    {
        if (request()->has($key) && !(bool)request()->get($key)) {
            $query->doesntHave($relation);
        } elseif (request()->has("{$key}_persons")
            || request()->has("{$key}_numbers")
            || request()->has("{$key}_obviative_codes")
        ) {
            $query->whereHas($relation, function (Builder $query) use ($key) {
                foreach (['person', 'number', 'obviative_code'] as $col) {
                    if (request()->has("{$key}_${col}s")) {
                        $query->whereIn($col, request()->get("{$key}_{$col}s"));
                    }
                }
            });
        }
    }
}
