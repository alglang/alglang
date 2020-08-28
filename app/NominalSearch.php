<?php

namespace App;

use App\Models\NominalForm;
use Illuminate\Support\Collection;

class NominalSearch extends ModelSearch
{
    protected function __construct()
    {
        $query = NominalForm::query();

        $filters = [
            'languages' => fn ($val) => $this->whereIn('forms.language_code', $val),
            'paradigm_types' => fn ($val) => $this->whereInRelation('structure.paradigm', 'paradigm_type_name', $val)
        ];

        parent::__construct($query, $filters);
    }

    public static function search(array $params): Collection
    {
        return (new self())->order()->constrain(collect($params))->get();
    }

    /**
     * Adds ordering clauses to the query
     *
     * @return self
     */
    public function order(): self
    {
        if (!queryHasJoin($this->query, 'nominal_structures')) {
            $this->query->join('nominal_structures', 'nominal_structures.id', '=', 'forms.structure_id');
        }

        if (!queryHasJoin($this->query, 'nominal_paradigms')) {
            $this->query->join('nominal_paradigms', 'nominal_paradigms.id', '=', 'nominal_structures.paradigm_id');
        }

        if (!queryHasJoin($this->query, 'nominal_paradigm_types')) {
            $this->query->join('nominal_paradigm_types', 'nominal_paradigm_types.name', '=', 'nominal_paradigms.paradigm_type_name');
        }

        $this->query->orderByRaw(<<<SQL
            CASE
                WHEN nominal_paradigm_types.has_pronominal_feature = 1 AND nominal_paradigm_types.has_nominal_feature = 0 THEN 1
                WHEN nominal_paradigm_types.has_pronominal_feature = 0 AND nominal_paradigm_types.has_nominal_feature = 1 THEN 2
                WHEN nominal_paradigm_types.has_pronominal_feature = 1 AND nominal_paradigm_types.has_nominal_feature = 1 THEN 3
                ELSE 4
            END,
            nominal_paradigms.name
        SQL);
        $this->query->orderByFeatures();
        $this->query->select('forms.*');
        return $this;
    }
}
