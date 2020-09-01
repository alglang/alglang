<?php

namespace App\Search;

use App\Models\Group;
use App\Models\Language;
use Illuminate\Support\LazyCollection;

class SmartSearch
{
    /** @var array */
    protected $searchMethods = [
        'searchForLanguage',
        'searchForGroup'
    ];

    public static function urlFor(string $query): ?string
    {
        return with(new self)->findUrl($query);
    }

    public function findUrl(string $query): ?string
    {
        $methods = new LazyCollection($this->searchMethods);

        return $methods->map(fn ($method) => $this->$method($query))
            ->first(fn ($url) => !is_null($url));
    }

    protected function searchForLanguage(string $query): ?string
    {
        $language = Language::where('code', 'LIKE', $query)
            ->orWhere('name', 'LIKE', $query)
            ->orWhere('alternate_names', 'LIKE', "%\"{$query}\"%")
            ->first();

        return $language ? $language->url : null;
    }

    protected function searchForGroup(string $query): ?string
    {
        $group = Group::where('name', 'LIKE', $query)->first();

        return $group ? $group->url : null;
    }
}
