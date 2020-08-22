<?php

use Illuminate\Database\Eloquent\Builder;

if (!function_exists('queryHasJoin')) {
    function queryHasJoin(Builder $query, string $table): bool
    {
        $joins = collect($query->getQuery()->joins);
        return $joins->pluck('table')->contains($table);
    }
}
