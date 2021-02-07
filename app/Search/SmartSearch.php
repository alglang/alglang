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
        $language = Language::whereRaw("code COLLATE utf8mb4_unicode_ci = ?", [$query])
            ->orWhereRaw("name COLLATE utf8mb4_unicode_ci = ?", [$query])
            ->orWhereRaw("alternate_names COLLATE utf8mb4_unicode_ci LIKE ?", ["%\"$query\"%"])
            ->first();

        return $language ? $language->url : null;
    }

    protected function searchForGroup(string $query): ?string
    {
        $group = Group::whereRaw("name COLLATE utf8mb4_unicode_ci = ?", [$query])->first();

        return $group ? $group->url : null;
    }
}
