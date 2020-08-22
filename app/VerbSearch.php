<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Dedicated class for running complex queries on verb forms
 */
class VerbSearch
{
    /** @var Builder */
    protected $query;

    /** @var array */
    protected $filters;

    protected function __construct()
    {
        $this->query = VerbForm::orderByFeatures();

        $this->filters = [
            'languages' => fn($val) => $this->whereIn('language_id', $val),
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
        return (new VerbSearch)->constrain(collect($params))->get();
    }

    /**
     * Constrains the underlying query based on provided parameters
     *
     * @param Collection $params The names and values for the constraints to
     *                           apply to the query
     * @return self
     */
    public function constrain(Collection $params): self
    {
        $this->order();

        $params->each(function ($value, $key) {
            $filter = $this->filters[$key];
            $filter($value);
        });

        return $this;
    }

    /**
     * Executes the query against the database and returns the results
     *
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->query->get();
    }

    /**
     * Adds ordering clauses to the query
     *
     * @return self
     */
    private function order(): self
    {
        $this->query->join('languages', 'forms.language_id', '=', 'languages.id')
                    ->orderBy('languages.name')
                    ->select('forms.*');
        return $this;
    }

    /**
     * Adds a clause to the query that $column should be one of the provided
     * $options
     *
     * @param string $column  The column to constrain
     * @param array  $options The options for values of the column
     * @return self
     */
    private function whereIn(string $column, array $options): self
    {
        $this->query->whereIn($column, $options);
        return $this;
    }

    /**
     * Adds a clause to the query that a given relation should have a column
     * that equals a given value
     *
     * @param string $relation  The relation the constraint should apply to
     * @param string $column    The column on the related model to constrain
     * @param mixed  $value     The value the column should have
     * @return self
     */
    private function whereRelation(string $relation, string $column, $value): self
    {
        $this->query->whereHas($relation, function ($query) use ($column, $value) {
            $query->where($column, $value);
        });
        return $this;
    }

    /**
     * Adds a clause to the query that a given relation should have a column
     * that contains one of a list of options
     *
     * @param string $relation  The relation the constraint should apply to
     * @param string $column    The column on the related model to constrain
     * @param array  $options   The options for values of the column
     * @return self
     */
    private function whereInRelation(string $relation, string $column, array $options): self
    {
        $this->query->whereHas($relation, function ($query) use ($column, $options) {
            $query->whereIn($column, $options);
        });
        return $this;
    }

    /**
     * Adds a clause to the query that the model should not have a relation
     *
     * @param string $relation  The relation the model should not have
     * @return self
     */
    private function whereNoRelation(string $relation): self
    {
        $this->query->whereDoesntHave($relation);
        return $this;
    }
}
