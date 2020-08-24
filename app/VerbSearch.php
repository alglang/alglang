<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Dedicated class for running complex queries on verb forms
 */
class VerbSearch extends ModelSearch
{
    protected function __construct()
    {
        $query = VerbForm::orderByFeatures();

        $filters = [
            'languages' => fn($val) => $this->whereIn('language_code', $val),
            'modes' => fn($val) => $this->whereInRelation('structure', 'mode_name', $val),
            'orders' => fn($val) => $this->whereInRelation('structure', 'order_name', $val),
            'classes' => fn($val) => $this->whereInRelation('structure', 'class_abv', $val),
            'negative' => fn($val) => $this->whereRelation('structure', 'is_negative', $val),
            'diminutive' => fn($val) => $this->whereRelation('structure', 'is_diminutive', $val),
            'subject' => fn($val) => $val ?: $this->whereNoRelation('structure.subject'),
            'primary_object' => fn($val) => $val ?: $this->whereNoRelation('structure.primaryObject'),
            'secondary_object' => fn($val) => $val ?: $this->whereNoRelation('structure.secondaryObject'),
            'subject_persons' => fn($val) => $this->whereInRelation('structure.subject', 'person', $val),
            'primary_object_persons' => fn($val) => $this->whereInRelation('structure.primaryObject', 'person', $val),
            'secondary_object_persons' => fn($val) => $this->whereInRelation('structure.secondaryObject', 'person', $val),
            'subject_numbers' => fn($val) => $this->whereInRelation('structure.subject', 'number', $val),
            'primary_object_numbers' => fn($val) => $this->whereInRelation('structure.primaryObject', 'number', $val),
            'secondary_object_numbers' => fn($val) => $this->whereInRelation('structure.secondaryObject', 'number', $val),
            'subject_obviative_codes' => fn($val) => $this->whereInRelation('structure.subject', 'obviative_code', $val),
            'primary_object_obviative_codes' => fn($val) => $this->whereInRelation('structure.primaryObject', 'obviative_code', $val),
            'secondary_object_obviative_codes' => fn($val) => $this->whereInRelation('structure.secondaryObject', 'obviative_code', $val)
        ];

        parent::__construct($query, $filters);
    }

    /**
     * Executes a search of the verb forms in the database
     *
     * @param array $params The names and values for the filters to apply to
     *                      the search
     * @return Collection A collection of verb forms that fit the search
     *                    criteria
     */
    public static function search(array $params): Collection
    {
        return (new self)->order()->constrain(collect($params))->get();
    }

    /**
     * Adds ordering clauses to the query
     *
     * @return self
     */
    protected function order(): self
    {
        $this->query->join('languages', 'forms.language_code', '=', 'languages.code')
                    ->orderBy('languages.name')
                    ->select('forms.*');
        return $this;
    }
}
