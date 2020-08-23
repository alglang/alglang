<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class NominalSearch extends ModelSearch
{
    protected function __construct()
    {
        $query = NominalForm::query();

        $filters = [
            'languages' => fn($val) => $this->whereIn('language_code', $val),
            'paradigm_types' => fn($val) => $this->whereInRelation('structure.paradigm', 'paradigm_type_name', $val)
        ];

        parent::__construct($query, $filters);
    }

    public static function search(array $params): Collection
    {
        return (new self)->constrain(collect($params))->get();
    }
}
